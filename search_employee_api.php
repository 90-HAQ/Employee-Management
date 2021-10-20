<?php

    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods:POST");

    require "Database.php";
    require "validation.php";

class SearchEmployeeApi {


    // This fucntion will take php object , conver to json format and return json

    
    function apiData($tableName,$name)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['Name'];
        $db = new Database();
        $employee = $db->searchEmployee($tableName,$name);
        echo json_encode($employee);
          
    }
}
    $tableName = "employ";
    $name = null;
    $Search = new SearchEmployeeApi();
    $Search->apiData($tableName,$name);
  
  

  
  
?>