<?php
class pages extends controller {

        function __construct($config=null) {
            //Load the core constructor
	    parent::__construct($config);
        
            //Load Facebook module
            $this->model('facebook', $this->config['config']['facebook'], 'facebook', 'facebook');
            $this->user_id = $this->facebook->require_login();
            
            $this->app_name = "Ideas App";
            $this->app_url = "ideasapp";
        }

	function index() {
		$this->views['user_id'] = $this->facebook->require_login();
		$this->views['message'] = 'Welcome to Ideas Page';
		$this->views['content'] = $this->view('pages/index', $view);
	}
}
