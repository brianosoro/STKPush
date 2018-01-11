<?php
include_once("all.php");

class Utilities{


public $database;
public $returnQueryObject;




function __construct(){

    $this->database = new DatabaseConfig(); 

}



public function countRows($query){
 $query_exe = mysqli_query($this->database->connection , $query);
 return mysqli_num_rows($query_exe);
}

public function sanitize($input){
 return trim(addslashes($input));
}

public function Query( $query ){
  //mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  return mysqli_query( $this->database->connection , $query );
}    

public function returnQuery( $query , $objectType , $collumnName = NULL ){
    
     $this->returnQueryObject = "";

      if($objectType == "array"){
         
         while ($row = mysqli_fetch_array($query)) {
                 $this->returnQueryObject[] = $row;
         }
      
      }else if($objectType == "assoc"){
       
         while ($row = mysqli_fetch_assoc($query)) {
                 $this->returnQueryObject[] = $row;
         }         
      
      }else if($objectType == "jsonAPI"){

         while ($row = mysqli_fetch_assoc($query)) {
                 $this->returnQueryObject = $row;
         }      
      }else if($objectType == "string"){

        while ( $row = mysqli_fetch_array($query) ) {
           $this->returnQueryObject = $row[$collumnName];
        }

      }


      return $this->returnQueryObject;

}



}
?>
