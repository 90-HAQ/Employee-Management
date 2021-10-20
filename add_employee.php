<?php

// shows database errors
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// include validations file
include "validation.php";

// include database class
include 'database.php';

class EMPLOYEE extends Database 
{
    function validate_emp_data($emp_name, $emp_phone, $emp_email)
    {
        $db = new DATABASE;
        $conn = $db->build_connection();

        // emp_id(auto_inc) / emp_name / phone // address // gender // email // dept
        $vali = new Validate;

        $check = false;

        // check validity of name
        $name = $vali->name_validate($emp_name); 

        // check validity of phone number
        $phone = $vali->phone_validate($emp_phone);

        // check validity of email
        $email = $vali->email_validate($emp_email);

        if($name == true && $phone == true && $email == true)
        {
            return 1;
        }
        else
        {
            return 0;
        }

    }

    function add_emp_data($name, $phone, $address, $department, $gender, $email)
    {
        $db = new DATABASE;
        $conn = $db->build_connection();

        $sql = "INSERT into employee (Name, Phone, Address, Deparment, Gender, Email) VALUES
         ('{$name}', '{$phone}', '{$address}', '{$department}', '{$gender}', '{$email}')";


        //$result = $conn->query($sql);

        $result = mysqli_query($conn, $sql) or die('failed');

        if($result)
        {
            echo "all ok";
            return 1;
        }
        else
        {
            echo "something went wrong";
            return 0;
        }


    }
}


//decode input request parameters and store them in an array.
$data = json_decode(file_get_contents("php://input"), true);

$name=$data["name"];
$phone=$data["phone"];
$address=$data["address"];
$department=$data["department"];
$gender=$data["gender"];
$email=$data["email"];

$emp = new EMPLOYEE;

$check = $emp->validate_emp_data($name, $phone, $email);

if($check == 1)
{
    echo ("<br>"."Data Valid"."<br>");
    
    $check1 = $emp->add_emp_data($name, $phone, $address, $department, $gender, $email);

    echo ("<br>".$check1."<br>");

    if($check1 == 1)
    {
        $display_message = array("Status_code"=>200,"Message"=>"Successfully Data Inserted!");
        print_r(json_encode($display_message));
    }
    else if($check1 == 0)
    {
        $display_message = array("Status_code"=>200,"Message"=>"Data Not Inserted!");
        print_r(json_encode($display_message));   
    }
}
