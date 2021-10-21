<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database{

    public function build_connection(){     //build sql database connection 
        $conn = new mysqli("localhost","root","","employee");
        if ($conn->connect_error){
            echo "Database Connection Error";
        }
        else{
            return $conn;
        }
    }
    public function close_connection($conn){   //close database connection
        $conn->close();
    }
    // This functioon is used to forget save password where email

    public function save_password_in_db($password,$email)    //here we set password in database
    {
        $conn = self::build_connection();                                           //connectivity with database
         $sql = "update user set userPassword='{$password}' where Email='{$email}'";   // set password         
        $result = $conn->query($sql) or exit("sql query failed");                    //Running Query
         self::close_connection($conn);   //connection close with database
    } 
     

    
     // This function is used to select user from table with the specific email.
     
    function search_employ_by_email($tableName,$email)        // searching employee by email
    {
        $conn = self::build_connection();
        $sql = "select * from ".$tableName ." WHERE email='{$email}'";
        $result = $conn->query($sql);
        self::close_connection($conn);
        if($result->num_rows > 0){
            return true;
        }
        else{
            return false;
        }
    }

     // This functioon is used to search employee with specific  name.
     
    
    function searchEmployee($tableName,$name){
        $conn = self::build_connection();
        $sql = "SELECT * FROM ".$tableName ." WHERE Name LIKE '%{$name}%'";
        $result = $conn->query($sql) or die("SQL Query Failed.");

        if(mysqli_num_rows($result) > 0 ){
            
            $output = $result->fetch_all();
            return $output;
        }else{

            return json_encode(array('No Search Found.'));
        }   
        self::close_connection($conn);
    }
    // Function to insert user or Employee in database.
      
     
    function insert($tableName,$perameter){
        if ($tableName == "user"){
            $innerPera = "Name,phone,address,gender,email,userPassword";
        }else{
            $innerPera = "Name,Phone,Address,Deparment,Gender,Email";
        }
        $S = implode("','",$perameter);
        $data = "'".$S."'";
        $conn = self::build_connection();
        $sql = "insert into $tableName($innerPera) values($data)";
        $conn->query($sql);
        self::close_connection($conn);
    }

   
}

?>
