<?php
    ini_set('display_errors' , 1); //The ini_set function will try to override the configuration found in PHP ini file.
    ini_set('display_startup_errors', 1);// It is a directive which determine whether the error will be displayed to the user or will be remain hidden.
    error_reporting(E_ALL);
    header("Content-Type: Application/json"); //sends  browser to inform him what the kind / format of a data he expects.
    header("Access-Control-Allow-Origin: *"); //any auccess our given name website
    header("Access-Control-Methods: post"); //response-type header. It is used to indicate which HTTP
    header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Methods,Access-Control-Allow-Headers,Authorization,X-Requested-With');

    include "validation.php";
    require "Database.php";

    class signup extends Database{

        private $email;
        private $name;
        private $phone;
        private $address;
        private $password;
        private $gender;
        
        private $table_name;
        private $data;
        private $parameter;
        
        // Function to get the information through the postman
        
        function get_data(){
            $data=json_decode(file_get_contents("php://input"),true);   //decde input request parameters and store them in an array.
    
            
            $name=$data["Name"];
            $phone=$data["phone"];
            $address=$data["address"];
            $gender=$data["gender"];
            $email=$data["email"];
            $password=$data["userPassword"];
            
            
            $parameter=array($name,$phone,$address,$gender,$email,$password); //store all data in array(parameter)

            if($parameter[0]=="" || $parameter[1]=="" || $parameter[2]=="" || $parameter[4]=="" || $parameter[5]==""){
                return false;}
            else{ return $parameter; }
        }
        
        // Function to validate request input.
         
        function validation($parameter){
            $checked=true;
            $objV = new Validate();                                          
            if(!$objV->name_validate($parameter[0]))  { $checked=false; }   // validating name                                                            
            if(!$objV->phone_validate($parameter[1]))  { $checked=false; }  // validating phone
            if(!$objV->email_validate($parameter[4]))  { $checked=false; }   // validating email                                           
            if(!$objV->password_validate($parameter[5]))  { $checked=false; }  // validating password
            return $checked;
        }
        
         // function to insert data in data base after successful signup by the user
         
        function insert_data($parameter){
            if(self::search_employ_by_email("user",$parameter[4])) //checking whether user already exists or not
            {
                echo json_encode(array('Message'=>'User Already Exist With This Email','status'=>"409"));  //status code 409 because user data added successfuly
            }
            else{
                $table_name="user";
                self::insert($table_name,$parameter);  //insert data for new user
                echo json_encode(array('Message'=>'SignUp Successfully :','status'=>"201"));  //status code 201 because user data added successfuly
            }
        } 
    }
        $objS = new signup();    //create object signup class 
        $gdata=$objS->get_data();   //function call and get array
            if(!$objS->get_data()){      //check get_data return true or false
                echo json_encode(array('Message'=>'Please Fill All the Fields :','status'=>"422")); //status code 422 because user not fill important field
            }
            else{
                if($objS->validation($gdata)){
                    $objS->insert_data($gdata);
                }
                else{
                    echo json_encode(array('Message'=>'Please Enter valid Data :','status'=>"422"));    //status code 422 because user enter invalid data
                }   
    }
?>