<?php

use DATABASE as GlobalDATABASE;

header("Access-Control-Allow-Origin: *"); 
// allows everyone to access your rest-api

header("Access-Control-Allow-Headers: access"); 
// all header access is allowed 

header("Access-Control-Allow-Methods: POST"); 
//header used to insert data

header("Content-Type: application/json"); 
// used to return json format

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
// the names of the headers that we will be used

// include database class
include 'database.php';


class ALL_EMPLOYEE extends Database
{
    private $table_name = "employee"; // database user table_name

    function display_all_employees()
    {
        $db = new DATABASE;

        $conn = $db->build_connection();
        $q2 = "select * from ".$this->table_name." ";
        $result = $conn->query($q2);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $db->close_connection($conn);
        return $data;

    }
}

$emp = new ALL_EMPLOYEE;

$db = $emp->display_all_employees();
echo json_encode($db);

?>