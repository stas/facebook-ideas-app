<?php
/**
 * Posts Model
 *
 * Provides example DB functionality for the Posts controller
 *
 * @package		MicroMVC
 * @author		David Pennington
 * @copyright	Copyright (c) 2009 MicroMVC
 * @license		http://www.gnu.org/licenses/gpl-3.0.html
 * @link		http://micromvc.com
 * @version		1.1.0 <7/7/2009>
 ********************************** 80 Columns *********************************
 */
class db_model extends base {

	//Check to see if the table exists - install it if not!
	function check_install() {

		//Get the database name
		$dbname = preg_replace('/.+?dbname=([a-z0-9_]+)/i', '$1', $this->db->config['dns']);

		//Create query
		$sql = "SELECT count(*) FROM information_schema.tables
				WHERE table_schema = '". $dbname. "' AND table_name = 'posts'";

		//Send query
		$result = $this->db->query($sql);

		//If the table is not found
		if( ! $result->fetchColumn()) {
			$this->create_table();
		}
	}


	// Create the posts table
	function create_table() {
		$sql = 'CREATE TABLE IF NOT EXISTS `ideas` (
			`id` int(11) NOT NULL auto_increment,
			`title` varchar(150) collate utf8_bin NOT NULL,
			`message` varchar(2000) collate utf8_bin NOT NULL,
			`aid` bigint(20) NOT NULL,
			`rating` float default 0,
			PRIMARY KEY  (`id`)
		      ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;';

		//Create the table
		$this->db->exec($sql);
	}

	function insert_idea($idea) {
		$this->db->insert('ideas', $idea);
	}

	/*
	 * Get all the posts
	 */
	function fetch() {
		return $this->db->get('ideas');
	}
	
	/*
	 * Get all mine posts
	 */
	function fetch_mine_posts($id, $value, $offset) {
		$this->db->from('ideas');
		$this->db->where('aid', $id);
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Count all mine posts
	 */
	function count_mine_posts($id) {
		$this->db->from('ideas');
		$this->db->where('aid', $id);
		return $this->db->count();
	}


}

