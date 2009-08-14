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

        function all($start = 0) {
            $perpage = 20;
            if($start < 1) $start = 1;
            $this->views['message'] = 'The top rated ideas.';
            $this->views['user_id'] = $this->user_id;
            $view['ideas'] = $this->db->fetch_all_ideas(($start - 1) * $perpage, $perpage);
            $howmany = $this->db->count_all_ideas();
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
            $this->views['content'] = $this->view('ideas/all', $view);
        }

        function top($start = 0) {
            $perpage = 20;
            if($start < 1) $start = 1;
            $this->views['message'] = 'The top rated ideas.';
            $this->views['user_id'] = $this->user_id;
            $view['ideas'] = $this->db->fetch_top_ideas($this->user_id, ($start - 1) * $perpage, $perpage);
            $howmany = $this->db->count_top_ideas($this->user_id);
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
            $this->views['content'] = $this->view('ideas/top', $view);
        }

        function friends($start = 0) {
            $perpage = 20;
            if($start < 1) $start = 1;
            $this->views['message'] = 'Ideas of your friends.';
            $this->views['user_id'] = $this->user_id;
            
            $view['ideas'] = $this->db->fetch_friends_ideas($this->facebook->api_client->friends_list, ($start - 1) * $perpage, $perpage);
            $howmany = $this->db->count_friends_ideas($this->facebook->api_client->friends_list);
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
            $this->views['content'] = $this->view('ideas/friends', $view);
        }

        function newest($start = 0) {
            $perpage = 20;
            if($start < 1) $start = 1;
            $this->views['message'] = 'The top rated ideas.';
            $this->views['user_id'] = $this->user_id;
            $view['ideas'] = $this->db->fetch_newest_ideas(($start - 1) * $perpage, $perpage);
            $howmany = $this->db->count_newest_ideas();
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
            $this->views['content'] = $this->view('ideas/newest', $view);
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