<?php
/**
 * Welcome Controller
 *
 * Shows several examples of how to load, call, and render pages.
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	Copyright (c) 2009 MicroMVC
 * @license		http://www.gnu.org/licenses/gpl-3.0.html
 * @link		http://micromvc.com
 * @version		1.1.0 <7/7/2009>
 ********************************** 80 Columns *********************************
 */

class welcome extends controller {

	/*
	 * Load a view that shows a welcome message
	 */
	function index() {

		//Create Some data
		$view['class'] = get_class($this);
		$view['function'] = __FUNCTION__;

		//Fetch it and set it as the layout content
		$this->views['content'] = $this->view('welcome/welcome', $view);

		//trigger_error('This is an error');
		//show_error('there was a problem');
	}


	/*
	 * Show and example of loading a API model and requesting data
	 */
	function twitter() {
		//See it live here
		//http://twitter.com/statuses/public_timeline.rss

		//Set my Username and password
		$options = array(
			'username'      => '',
			'password'      => '',
			'type'          => 'json' //or 'xml'
		);

		//Load the twitter model and create object
		$this->library('twitter_api', $options);


		//Get current user_timeline
		$object = $this->twitter_api->public_timeline();

		//If empty - fetch error
		if(!$object) {
			$object = $this->twitter_api->error();
		}

		//Register this object for the view
		$view['object'] = $object;

		//Render the view
		$this->views['content'] = $this->view('welcome/twitter', $view);

	}
}