<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database{

    public function build_connection()
    {   
        //build sql database connection 

        $conn = new mysqli("localhost","root","","emp");

        if ($conn->connect_error)
        {
            echo "Database Connection Error";
        }
        else
        {
            // to check if the database connection is established or not 
            //echo "connected"; 
            return $conn;
        }
        
    }
    public function close_connection($conn)
    {
        //close database connection
        $conn->close();
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

        }else{

        echo json_encode(array('message' => 'No Search Found.', 'status' => false));

        }   
        self::close_connection($conn);
        return $output;
    }
   
}

?>
