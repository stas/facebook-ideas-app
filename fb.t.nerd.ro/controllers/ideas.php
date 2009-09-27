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
		
		$this->app_name = "Ideas App";
		$this->app_url = "ideasapp";
		
		//Check to see if the table is installed
		//$this->db->check_install();
	}
        
        function index() {
            
        }
        
        function delete($id) {
            $this->views['user_id'] = $this->user_id;
            $idea = $this->db->fetch_by_id($id);
            if($idea[0]->aid == $this->user_id)
                $this->db->delete_by_id($id);
            $this->views['content'] = $this->view('ideas/delete');
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
            $view['ideas'] = $this->db->fetch_top_ideas(($start - 1) * $perpage, $perpage);
            $howmany = $this->db->count_top_ideas();
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
            if($howmany != 0) {
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
            else {
                $view['ideas'] = $this->db->fetch_top_ideas($this->facebook->api_client->friends_list, 5, 5); // Get top 5 ideas
                $howmany = 5;
                unset($view['hasposts']);
                $this->views['content'] = $this->view('ideas/no_friends', $view);
            }
            
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
        
        function see($id, $rated = false) {
            $this->views['user_id'] = $this->user_id;
            $this->views['message'] = 'This is you personal page.';
            $view['idea'] = $this->db->fetch_by_id($id);
            if($rated)
                $view['rated'] = true;
            $this->views['content'] = $this->view('ideas/see', $view);
        }
        
        function rate($id, $rate) {
            $this->views['user_id'] = $this->user_id;
            $this->views['message'] = 'This is you personal page.';
            
            $cookie = $this->app_url;

            if(is_numeric($rate) && $rate > 0 && $rate < 6 && is_numeric($id)) {
                $r = $this->db->get_rating_by_id($id);
                if($r[2] != $this->user_id && $_COOKIE[$cookie] != $cookie.'_'.$id) { //authors can not vote own ideas
                    if($r[0] == 0)
                        $new_rating = $rate;
                    else
                        $new_rating = $r[0] + $rate;
                    
                    $this->db->set_rating_by_id($id, $new_rating, $r[1] + 1);
                    $deadline = 86400 + time(); // remember voters for 24 hours
                    setcookie($cookie, $cookie.'_'.$id, $deadline);
                    $voted = true;
                }
                else
                    $voted = false;
            }
            else {
                trigger_error('Kidding eh?');
            }
            
            if($voted)
                $this->see($id, true);
            else
                $this->see($id, false);
        }
        
    }
