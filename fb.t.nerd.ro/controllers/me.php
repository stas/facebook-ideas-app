<?php
class me extends controller {

	/*
	 * We need facebook!
	 */
	function hooks() {
		$this->data['content'] = $this->hooks->run_hook(
						'facebook_init',
						array(
							$facebook['appapikey'],
							$facebook['appsecret']
						)
					);
		var_dump($this);
	}

	/*
	 * Profile page
	 */
	function index() {

		//Create Some data
		$view['class'] = get_class($this);
		$view['function'] = __FUNCTION__;

		$this->views['content'] = $this->view('me/index', $view);
		
		//trigger_error('This is an error');
		//show_error('there was a problem');
	}
	
}