<?php 
/*
 * database connection and calls
 */
class DB {
	
	// privates
	var $server;
	var $dbname;
	var $dbtable;
	var $user;
	var $pass;
	var $conn;
	var $salt;
	
	/**
	 * The constructor.
	 */
	function __construct() {
	
		//import the site-specific database info
		$this->server = "localhost";
		$this->user = "root";
		$this->pass = "root";
		$this->dbname = "canvas-letters";
		$this->dbtable = "canvas-letters";
		$this->salt = "canvas-letters";
		
		//start the connection
		$this->conn = @mysql_connect($this->server,$this->user,$this->pass);
		@mysql_select_db($this->dbname, $this->conn);

	}
	
	/**
	 * Generic MySQL select query 
	 */
	function selectQuery($sql) {
		
		//run the initial query
		$result = @mysql_query($sql);
		
		//condition : if it is a single value, return it
		if (@mysql_num_fields($result) === 1 && @mysql_num_rows($result) === 1) {
			list($return) = @mysql_fetch_row($result);
		
		// it is more than a single row, start an array to contain each object...
		} else {
			
			//start the var to return
			$return = array();
		
			//for each row in the result, start a new object
			while ($row = @mysql_fetch_object($result)) {
				$return[] = $row;
			}
		}
		
		return $return;
	}


	/**
	 * Generic MySQL update query 
	 */
	function updateQuery($sql) {
		
		//run the initial query
		$result = mysql_query($sql);
		
		if ($result) {
			$return = true;
		} else {
			$return = false;
		}
		
		return $return;
	}
	
	
	/**
	 * Generic MySQL add query 
	 */
	function addQuery($sql) {
		
		//run the initial query
		$result = mysql_query($sql);
		
		if ($result) {
			$return = mysql_insert_id();
		} else {
			$return = false;
		}
		
		return $return;
	}
	
	
	
	/*
	 *
	 * Site-specific calls
	 *
	 */
	
	/*
	 * get slug
	 */
	function get($slug) {
		
		$slug = mysql_real_escape_string($slug);
		$slug = strip_tags($slug);
		$slug = stripslashes($slug);
		
		$sql = "SELECT *, date_format(dateadded, '%W %D %M %Y, %k:%i') as date_added from `".$this->dbtable."` where `slug` = '".$slug."' limit 0,1";
		$result = $this->selectQuery($sql);
		
		return $result[0];
	}
	
	/*
	 * increase view count
	 */
	function increaseViewCount($slug) {
		$sql = "UPDATE `".$this->dbtable."` set views = views + 1 WHERE `slug` = '".$slug."'";
		$result = $this->updateQuery($sql);
		return $result;
	}
	
	/*
	 * get count
	 */
	function getDBCount() {
		$sql = "SELECT count('id') as dbcount FROM `".$this->dbtable."`";
		$result = $this->selectQuery($sql);
		return $result;
	}
	
	/*
	 * save
	 */
	function save($post) {
		
		require_once('pseudo-crypt.php');
		
		// sanitise
		foreach($post as $key => $postitem) {
			$postitem = strip_tags($postitem);
			$postitem = mysql_real_escape_string($postitem);
			$post[$key] = $postitem;
		}
		
		//insert 
		$sql = "INSERT into `".$this->dbtable."` 
			(
				blockColour,
				canvasColour,
				blockSize,
				textString,
				clearance,
				breakWord,
				ordering,
				do_loop,
				animate, 
				speed,
				ip,
				views, 
				dateadded
			) values (
				'".$post['blockColour']."', 
				'".$post['canvasColour']."', 
				'".$post['blockSize']."', 
				'".$post['textString']."', 
				'".$post['clearance']."', 
				'".$post['breakWord']."', 
				'".$post['ordering']."', 
				'".$post['do_loop']."', 
				'".$post['animate']."', 
				'".$post['speed']."', 
				'".$_SERVER['REMOTE_ADDR']."', 
				'0', 
				 NOW()
			)";

		$id = $this->addQuery($sql);
		
		$slug = PseudoCrypt::udihash($id);
		
		$url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $slug;
		
		$post['url'] = $url;
		$post['slug'] = $slug;
		$post['id'] = $id;
		
		
		$sql = "UPDATE `".$this->dbtable."` set `slug` = '".$slug."' WHERE `id` = '".$id."'";
		$result = $this->updateQuery($sql);
		
		$return = array( 
			'success' => true,
			'details' => $post
		);
		
		//return $return;
		//return $slug;
		return $post;
	}
}