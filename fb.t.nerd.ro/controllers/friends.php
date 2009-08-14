<?php
    class friends extends controller {

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
        

        
    }