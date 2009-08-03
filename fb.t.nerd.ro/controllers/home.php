<?php
class home extends controller {

	/*
	 * Profile page
	 */
	function index() {

		$this->model('facebook', $this->config['config']['facebook'], 'facebook', 'facebook');
		//Create Some data
		$view['class'] = get_class($this);
		$view['function'] = __FUNCTION__;
		$this->views['user_id'] = $this->facebook->require_login();
		$this->views['message'] = 'Welcome to Future Responsible Bussiness';
		$this->views['content'] = $this->view('home/index', $view);
		
		//trigger_error('This is an error');
		//show_error('there was a problem');
	}
	
}