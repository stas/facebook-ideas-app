<?php
class home extends controller {

	/*
	 * Profile page
	 */
	function index() {

		$this->model('facebook', $this->config['config']['facebook'], 'facebook', 'facebook');
		//Create Some data
		$view['class'] = get_class($this);
		$this->views['user_id'] = $this->facebook->require_login();
		$this->views['message'] = 'Welcome to Future Ideas App';
		$this->views['content'] = $this->view('home/index', $view);
		
		//Load the database
		$this->load_database();
		 
		//Load the Model for this controller
		$this->model('db_model', null, 'db');
		
		// Create the profile box
		$the_box =  $this->get_user_profile_box($this->facebook->require_login());
		// Initiate the profile box content
		$this->facebook->api_client->profile_setFBML(null, $this->facebook->require_login(), null, null, null, $the_box);
		// Update the profile box content each time canvas is visited
		$this->facebook->api_client->call_method(
			'facebook.profile.setFBML',
			array(
				'uid' => $this->facebook->require_login(),
				'profile' => $the_box,
				'profile_main' => $the_box
			)
		);
		$this->app_url = "ideasapp";

	}
	
	function get_user_profile_box($uid) {
		$this->app_url = "ideasapp";
		$ideas = $this->db->fetch_mine_top_ideas($uid, null);
		$content = "<h3>Submitted ideas</h3>";
		$content .= "<ul>";
		foreach($ideas as $idea) {
			$content .= '<li><a href="http://apps.facebook.com/'.$this->app_url.'">'.$idea->title.'</a></li>';
		}
		$content .= "</ul>";
		$content .= '<img src="http://fb.t.nerd.ro/fb.t.nerd.ro/views/images/banner.png" alt="" />';
		$content .= '<hr/>';
		$content .= 'Got your own idea? <a href="http://apps.facebook.com/'.$this->app_url.'/me/new_idea">Post it now</a>!';
		
		return $content;
	}
	
}
