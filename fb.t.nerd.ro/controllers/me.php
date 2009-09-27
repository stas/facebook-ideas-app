<?php
class me extends controller {

	function __construct($config=null) {
		//Load the core constructor
		parent::__construct($config);
		 
		//Load the database
		$this->load_database();
		 
		//Load the Model for this controller
		$this->model('db_model', null, 'db');
		
		//Load Facebook module
		$this->model('facebook', $this->config['config']['facebook'], 'facebook', 'facebook');
		$this->user_id = $this->facebook->require_login();
		
		$this->app_name = "Ideas App";
		$this->app_url = "ideasapp";
		
		//Check to see if the table is installed
		//$this->db->check_install();
	}

	/*
	 * Profile page
	 */
	function index() {

		$view['class'] = get_class($this);
		$view['function'] = __FUNCTION__;
		$this->views['user_id'] = $this->user_id;
		$this->views['message'] = 'This is you personal page.';
		$this->views['content'] = $this->view('me/index', $view);
		
		//trigger_error('This is an error');
		//show_error('there was a problem');
	}
	
	function new_idea() {
		$this->views['user_id'] = $this->user_id;
		$this->views['message'] = 'Submit your ideas now.';
		$this->views['no_wrapper'] = __FUNCTION__;
		$needed_age = date("Y") - 25; // Calculate required age
		$user_age = $this->facebook->api_client->users_getInfo($this->user_id, 'birthday');
		if($user_age[0]['birthday']) {
			$user_age = explode(',', $user_age[0]['birthday']);
			$user_age = intval(trim($user_age[1]));
		} else {
			$user_age = $needed_age = date("Y");
		}
		
		if ($user_age < $needed_age) // Age missed
			$this->views['content'] = $this->view('me/new_idea_denied');
		else
			$this->views['content'] = $this->view('me/new_idea');
	}
	
	function add() {
		//Load the validation library
		$this->library('validation');
		$this->views['user_id'] = $this->user_id;
		
		$config = array();
		$config['title'] = 'required|max_length[150]';
		$config['text'] = 'required|max_length[2000]';
		
		if($this->validation->run($config) == FALSE) {
			$views['failed'] = true;
			$this->views['content'] = $this->view('me/new_idea', $views);
			return;
		} else {
			$data = array(
				'title' => post('title'),
				'message' => post('text'),
				'aid' => $this->user_id
			);
			$this->db->insert_idea($data);
			
			// Create a stream
			if(in_array('publish_stream',explode(',',$this->facebook->fb_params['ext_perms']))) {
				$message = "I published a new idea: ".post('title')."!";
				/*
				$action_links = array(
						      array(
							'text' => '<fb:publisher-link>Check out Ideas App </fb:publisher-link>',
							'href' => 'http://apps.facebook.com/'.$this->app_url.'/ideas/newest'
						));
				*/
				$this->facebook->api_client->stream_publish($message/*, null, $action_links*/);
			}
		}

		$this->views['content'] = $this->view('me/idea_added');
		return TRUE;
	}
	
	function invite() {
		$this->views['user_id'] = $this->user_id;
		$this->views['message'] = 'Invite some friends to support your ideas.';
		$app_name = $this->app_name;
		$app_url = $this->app_url;
		$invite_href = "me/all";
		
		$views = array();
		$view = null;
		// Taken from FB wiki
		if(isset($_POST["ids"])) {
			$views['invited'] = "<center>Thank you for inviting ".sizeof($_POST["ids"])." of your friends on
						<b><a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a></b>.<br /><br />\n";
			$views['invited'] .= "<h2><a href=\"http://apps.facebook.com/".$app_url."/\">Click here to return to ".$app_name."</a>.</h2></center>";
			
			$view = 'me/invite';
		} else {
			// Retrieve array of friends who've already authorized the app.
			$fql = 'SELECT uid FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1='.$this->user_id.') AND is_app_user = 1';
			$_friends = $this->facebook->api_client->fql_query($fql); // Extract the user ID's returned in the FQL request into a new array.
			$friends = array();
			if (is_array($_friends) && count($_friends)) {
				foreach ($_friends as $friend) {
					$friends[] = $friend['uid'];
				}
			}
			// Convert the array of friends into a comma-delimeted string.
			$friends = implode(',', $friends);
			// Prepare the invitation text that all invited users will receive.
			$views['content'] = "Hi,
				\n
				I've come up with an amazing idea for the <a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a>.
				\n
				I've just posted my idea on the Challenge  website and I would really like you to take a look at it and post your comments on the site. All you have to do is install the application and go to \"My friends' ideas\". The more comments or votes I can get for my idea, the more chances I have to win the Mini Notebook!
				\n
				You can enter the Challenge by posting your own idea too! If you decide to submit your own idea, please let me know and I'll post my comments on yours as well. 
				\n
				Thanks!
				\n
				<fb:name uid=\"".$this->user_id."\" firstnameonly=\"true\" shownetwork=\"false\"/>".
				"<fb:req-choice url=\"".$this->facebook->get_add_url()."\" label=\"Put ".$app_name." on your profile\"/>"; 
			$views['app_name'] = $app_name;
			$views['app_url'] = $app_url;
			$views['invite_href'] = $invite_href;
			
			$view = 'me/invite';
		}
		
		$this->views['content'] = $this->view($view, $views);
	}
	
	function all($start = 0) {
		$perpage = 20;
		if($start < 1) $start = 1;
		$this->views['message'] = 'Here are all the ideas you submitted.';
		$this->views['user_id'] = $this->user_id;
		$view['ideas'] = $this->db->fetch_mine_ideas($this->user_id, ($start - 1) * $perpage, $perpage);
		$howmany = $this->db->count_mine_ideas($this->user_id);
		if($howmany >= $perpage) {
		    if(($start * $perpage) >= $howmany)
			    $view['hasposts'] = -1; // at the end
		    elseif($start == 1)
			    $view['hasposts'] = 1; //on start
		    elseif($start <= $howmany)
			    $view['hasposts'] = 0; //during pagination
		}
		else
		    unset($view['hasposts']); // no pagination
		$view['page'] = $start;
		$this->views['content'] = $this->view('me/all', $view);
	}
	
}
