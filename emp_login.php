<?php

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

class USER extends Database
{
    
    private $table_name = "user"; // database user table_name

    function validation($uemail1,$upassword1) // check the validation of user credentials
    {
        $t1 = $t2 = true;
        if(!filter_var($uemail1, FILTER_VALIDATE_EMAIL))
        {
            //status code 422 because user enter invalid email pattern;
            $display_message = array("Status_code"=>422, "Message"=>'Invalid Email Pattern');
            print_r(json_encode($display_message));
            $t1 = false;
        }
        if(strlen($upassword1) < 5)
        {
            //status code 422 because user password length is less than 10 digits / characters.
            $display_message = array("Status_code"=>422, "Message"=>'Your password must be at least of 5 digits/characters!');
            print_r(json_encode($display_message));
            $t2 = false;
        }
        if(!$t1 == false && !$t2 == false)
        {
            return 1;
        }
        else
        {
            return 0;
        }
        
    }

    function login($uemail1,$upassword1) // user login function
    {


        $db = new DATABASE;
        $conn = $db->build_connection();

        // sql query to check if email and password of user exists or not in DB.

        $q1 = "select * from ".$this->table_name ." WHERE Userpassword ='{$upassword1}'";
        //print_r ($query);

        $result1 = mysqli_query($conn, $q1);

        $t1 = 0;

        if (mysqli_num_rows($result1) > 0) 
        {
            //echo ("<br>"."90 haq");
           $t1 = 1;
        }
        else
        {
            //echo ("<br>"."nothing");
            $t1 = 0;
        }

        $q2 = "select * from ".$this->table_name ." WHERE Email ='{$uemail1}'";

        $result2 = mysqli_query($conn, $q2);

        $t2 = 0;

        if (mysqli_num_rows($result2) > 0) 
        {
            //echo ("<br>"."90 haq");
           $t2 = 1;
        }
        else
        {
            //echo ("<br>"."nothing");
            $t2 = 0;
        }


        if($t1 == 1 && $t2 == 1)
        {
            //echo "good";
            return 1;
        }
        else
        {
            //echo "not good";
            return 0;
        }
    }
}

//decode input request parameters and store them in an array.
$data = json_decode(file_get_contents("php://input"), true);

//Check if request method is not $_POST send error message and terminate program.
if($_SERVER["REQUEST_METHOD"] != "POST")
{
    //status code 404 because request method is wrong
    $message_display=array("Status_code"=>404,"Message"=>'Page not found');
    print_r(json_encode($message_display));
    exit();
}

// store user decoded data in respective variables
$uemail = $data["email"];
//echo $uemail; 
// store user decoded data in respective variables
$upassword = $data["password"];
//echo $upassword;


// creating object of class database
$database = new DATABASE(); 
// build database connection
$database->build_connection();

// creating object of the class USER
$system = new USER(); 


// check validations on user given inputs
$check = $system->validation($uemail,$upassword);

// weather $check i empty or not
//echo $check;


if($check == 0) 
{
    // if function validate returns false value close the execution
    echo ("<br>"."zero"."<br>");
    exit();
}
else if($check == 1) 
{
    // if function validate returns true the proceed 
    echo ("<br>"."Check is true for validation"."<br>");
    // calling login function to check user login credentials
    $flag = $system->login($uemail,$upassword); 
}


if($flag == 1)
{
    //if password and email are matched display this message
    $display_message = array("Status_code"=>200,"Message"=>"Successfully Login!","User_Id"=>"$uemail","Password"=>"$upassword]");
    print_r(json_encode($display_message));
}
else if($flag == 0)
{

    //if password and email are matched display this message
    $display_message = array("Status_code"=>200,"Message"=>"Something went wrong!","User_Id"=>"$uemail","Name"=>"$upassword]");
    print_r(json_encode($display_message));
}

?>
