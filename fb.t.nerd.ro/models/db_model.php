<?php
/**
 * Ideas Model
 *
 * Provides example DB functionality for the Ideas controller
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
				WHERE table_schema = '". $dbname. "' AND table_name = 'ideas'";

		//Send query
		$result = $this->db->query($sql);

		//If the table is not found
		if( ! $result->fetchColumn()) {
			$this->create_table();
		}
	}


	// Create the ideas table
	function create_table() {
		$sql = 'CREATE TABLE IF NOT EXISTS `ideas` (
			`id` int(11) NOT NULL auto_increment,
			`title` varchar(150) collate utf8_bin NOT NULL,
			`message` varchar(2000) collate utf8_bin NOT NULL,
			`aid` bigint(20) NOT NULL,
			`rating` tinyint(4) NOT NULL default \'0\',
			`votes` tinyint(4) NOT NULL default \'0\',
			`ratio` float NOT NULL default \'0\',
			PRIMARY KEY  (`id`)
		      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;';
		//Create the table
		$this->db->exec($sql);
	}

	function insert_idea($idea) {
		$this->db->insert('ideas', $idea);
	}

	/*
	 * Get all the ideas
	 */
	function fetch() {
		return $this->db->get('ideas');
	}
	
	/*
	 * Get the idea
	 */
	function fetch_by_id($id) {
		$this->db->from('ideas');
		$this->db->where('id', $id);
		return $this->db->get();
	}
	
	/*
	 * Delete the idea
	 */
	function delete_by_id($id) {
		$this->db->where('id', $id);
		return $this->db->delete('ideas');
	}
	
	/*
	 * Get all mine ideas
	 */
	function fetch_mine_ideas($id, $value, $offset) {
		$this->db->from('ideas');
		$this->db->where('aid', $id);
		$this->db->order_by('id', 'DESC');
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Get all mine ideas
	 */
	function fetch_mine_top_ideas($id, $value = 5, $offset = 5) {
		$this->db->from('ideas');
		$this->db->where('aid', $id);
		$this->db->order_by('ratio', 'DESC');
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Get all friends ideas
	 */
	function fetch_friends_ideas($friends, $value, $offset) {
		$this->db->from('ideas');
		$this->db->where('aid', $friends);
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Count all friends ideas
	 */
	function count_friends_ideas($friends) {
		$this->db->from('ideas');
		$this->db->where('aid', $friends);
		return $this->db->count();
	}
	
	/*
	 * Count all mine ideas
	 */
	function count_mine_ideas($id) {
		$this->db->from('ideas');
		$this->db->where('aid', $id);
		return $this->db->count();
	}

	/*
	 * Get top ideas
	 */
	function fetch_top_ideas($value, $offset) {
		$this->db->from('ideas');
		$this->db->order_by('ratio', 'DESC');
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Count top ideas
	 */
	function count_top_ideas() {
		$this->db->from('ideas');
		$this->db->order_by('ratio', 'DESC');
		return $this->db->count();
	}

	/*
	 * Get newest ideas
	 */
	function fetch_newest_ideas($value, $offset) {
		$this->db->from('ideas');
		$this->db->order_by('id', 'DESC');
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Count newest ideas
	 */
	function count_newest_ideas() {
		$this->db->from('ideas');
		$this->db->order_by('id', 'DESC');
		return $this->db->count();
	}
	
	/*
	 * Get all ideas
	 */
	function fetch_all_ideas($value, $offset) {
		$this->db->from('ideas');
		if($value)
			$this->db->limit($offset, $value);
		else
			$this->db->limit($offset);
		return $this->db->get();
	}
	
	/*
	 * Count all ideas
	 */
	function count_all_ideas() {
		$this->db->from('ideas');
		return $this->db->count();
	}

	/*
	 * Get the rating for the id
	 */
	function get_rating_by_id($id) {
                $this->db->from('ideas');
                $this->db->where('id =', $id);
                $this->db->select('aid, rating, votes');
		$r = $this->db->get();
		return array($r[0]->rating, $r[0]->votes, $r[0]->aid);
	}
	
	/*
	 * Set the new rating for the id
	 */
	function set_rating_by_id($id, $r, $v) {
		$ratio = 0;
		if($r > 0 && $v > 0)
			$ratio = $r/$v;
			
		$data = array(
			'rating' => $r,
			'votes' => $v,
			'ratio' => $ratio
		);
		$where = array(
			'id' => $id
		);
                $this->db->update('ideas', $data, $where);
	}

}

