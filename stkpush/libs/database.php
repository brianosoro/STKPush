<?php
include_once("constants.php");

class DatabaseConfig {

public $connection;

	function __construct(){
	  $this->connect();	
	}

	 public function connect(){
		try{	
			
			$this->connection  = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			if(!$this->connection) {                                              
			 echo "No database connection available";	
			} else {
			 mysqli_query($this->connection , 'SET CHARACTER SET utf8');
			}

		} catch(Exception $e){
		   
		   throw new Exception("Error communicating to database server, please refresh the page!!", 1,$e);
		
		}


	}

}

?>