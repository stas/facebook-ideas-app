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
		
		$this->app_name = "Responsible Business";
		$this->app_url = "fdbussiness";
		
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
		$needed_age = date("Y") - 25; // Calculate required age
		$user_age = $this->facebook->api_client->users_getInfo($this->user_id, 'birthday');
		if($user_age[0]['birthday']) {
			$user_age = explode(',', $user_age[0]['birthday']);
			$user_age = intval(trim($user_age[1]));
		} else {
			$user_age = $needed_age = date("Y");
		}
		
		if ($user_age < $needed_age)
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
		}

		$this->views['content'] = $this->view('me/idea_added');
		return TRUE;
	}
	
	function invite() {
		$this->views['user_id'] = $this->user_id;
		$this->views['message'] = 'Invite some friends to support your ideas.';
		$app_name = $this->app_name;
		$app_url = $this->app_url;
		$invite_href = "me/index";
		
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
			$views['content'] = "<fb:name uid=\"".$this->user_id."\" firstnameonly=\"true\" shownetwork=\"false\"/> has started using
					<a href=\"http://apps.facebook.com/".$app_url."/\">".$app_name."</a>
					and thought you'll like it too!\n".
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
		$this->views['message'] = 'Here are all the ideas you commited.';
		$this->views['user_id'] = $this->user_id;
		$view['ideas'] = $this->db->fetch_mine_posts($this->user_id, ($start - 1) * $perpage, $perpage);
		$howmany = $this->db->count_mine_posts($this->user_id);
		if(($start) * $perpage >= $howmany)
			$view['hasposts'] = -1; // at the end
		elseif($start == 1)
			$view['hasposts'] = 1; //on start
		elseif($start <= $howmany)
			$view['hasposts'] = 0; //during pagination
		$view['page'] = $start;
		$this->views['content'] = $this->view('me/all', $view);
	}
	
}