<?php
    class ideas extends controller {
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
        
        function index() {
            
        }

        function all() {
            
        }

        function top_ideas() {
            
        }

        function new_ideas() {
            
        }
        
        function see($id) {
            $view['idea'] = $this->db->fetch_by_id($id);
            $this->views['content'] = $this->view('ideas/see', $view);
        }
        
        function rate($id, $rate) {
            $this->views['user_id'] = $this->user_id;
            $this->views['message'] = 'This is you personal page.';
            if(is_numeric($rate) && $rate > 0 && $rate < 6 && is_numeric($id)) {
                $cur_rating = $this->db->get_rating_by_id($id);
                if($cur_rating == 0)
                    $new_rating = $rate;
                else
                    $new_rating = ($cur_rating + $rate) / 2;
                    
                $this->db->set_rating_by_id($id, $new_rating);
            }
            else {
                trigger_error('Kidding eh?');
            }
            
            $this->see($id);
        }
        
    }