<?php
session_start();   //start session
header('Content-Type:application/json');   //Here we set header as json Format
include 'Database.php';

 class forgetPassword extends Database   {
    public function check_email($semail)    //email verification it exist or not
    {   
          
        $conn= self::build_connection();     //connection building with database
        $sql = "select * from user WHERE email='{$semail}'";   //check email exist in database or not
        echo json_encode($sql);
        $result = $conn->query($sql)  or die("not working");      //sql query running
        echo json_encode($result);
        self::close_connection($result);  //Close database Connection
       if( $semail == "")               //if empty request then show error
       { 
           $msg = array("status"=>"204","message"=>"no content recieve");  //message in array form
           echo json_encode($msg);                                        //conversion in json form and printing on console
           
       }
       else if($result->num_rows > 0)       //if value >0 then call inside functiom
       {
           self::send_email($semail);
       }
       else
       { 
            $msg = array("status"=>"404","message"=>"result not found");   //if email not exist then show this
            echo json_encode($msg);   
       } 
    }
     
     public function send_email($remail)   //here we genrate password and send to user email
    {
        $password = rand(1000000,9999999);        // Random number and set as password 
        
        $to_email = "khawars282@gmail.com";
        $subject = "email test via php";
        $body = "hi,this is your seven digit password(one time pin)-{$password}";
        $headers = "from: skhawar0303@gmail.com";
        
        if (mail($to_email, $subject, $body, $headers)) {   //PHP mail Function Use For Sending mail
            $msg = array("status"=>"200","message"=>"password send on '{$to_email}'");
            echo json_encode($msg);
            self::save_password_in_db($password,$remail);        //call Function saving password in DATABASE
        }
        else 
        {
            $msg = array("status"=>"500","message"=>"internal server error");  //Any Error occur
        }
    }
 }

//Data get from user through Postmen
$data  = json_decode(file_get_contents('php://input'),true);     //Postman Input
$email = $data['email'];                                       //Fecth email in variable
$_SESSION['email'] = $email;                                     //Creating Session for Password_check page for checks
//Object and Function Call
$password  = new forgetPassword();                                   // object initilization of class
$password->check_email($email);                                      // call function through object



?>
