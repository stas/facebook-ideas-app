<?php
class me extends controller {

	/*
	 * Profile page
	 */
	function index() {

		$this->model('facebook', $this->config['config']['facebook'], 'facebook', 'facebook');
		//Create Some data
		$view['class'] = get_class($this);
		$view['function'] = __FUNCTION__;
		$this->views['user_id'] = $this->facebook->require_login();
		$this->views['message'] = 'Submit your ideas now.';
		$this->views['content'] = $this->view('me/index', $view);
		
		//trigger_error('This is an error');
		//show_error('there was a problem');
	}
	
	function new_idea() {
		$this->views['content'] = $this->view('me/new_idea');
		return TRUE;
	}
	
	function add() {
		//Load the validation library
		$this->library('validation');

		$config = array();
		$config['title'] = 'required|max_length[150]';
		$config['text'] = 'required|max_length[2000]';
		
		if($this->validation->run($config) == FALSE) {
			$views['failed'] = true;
			$this->views['content'] = $this->view('me/new_idea', $views);
			return;
		}
		else {
			//echo post('title');
			//echo post('text');
			$a = 1;
		}

		$this->views['content'] = $this->view('me/idea_added');
		return TRUE;
	}
	
}