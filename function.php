<?php
    error_reporting(E_ERROR | E_PARSE);
    include 'connect.php';   

    function insertUser(){    
        global $conn;    
        
        $response = new stdClass();
        $response->err = 0;
        $response->message = "";

        $data = json_decode(file_get_contents("php://input"), true);
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];    
        $email = $data['email'];
        $phone = $data['phone'];

        $checkUserInput = validateUserData($data);
        if(!$checkUserInput->isvalid){
            $response->message = $checkUserInput->message;
            return json_encode($response);
        }      
        $checkUserExists = "SELECT * FROM tbl_users WHERE email = '$email'";
        $checkUserResult = mysqli_query($conn, $checkUserExists);
        if(mysqli_num_rows ($checkUserResult) == 1) {                    
            $response->err = 1;
            $response->message = "Sorry! User already exist with email ".$email;                                     
        }else{
            $sql = "INSERT INTO tbl_users (firstName, lastName, email, phone) VALUES ('$firstName', '$lastName', '$email', '$phone')";   
            if(mysqli_query($conn, $sql)) {      
                $last_id = mysqli_insert_id($conn);  
                $response->message = "user added successfully!";                                     
            
                $sqlSelect = "SELECT * FROM tbl_users WHERE id = $last_id";
                $result = mysqli_query($conn, $sqlSelect);
                if($result) {                        
                    $insertedRecord = mysqli_fetch_assoc($result);                        
                    $response->userData = $insertedRecord; 
                } else {
                    echo "Error selecting the inserted record: " . mysqli_error($conn);
                }
            }else{
                $response->err = 1;
                $response->message = "Data not inserted.";    
            }
        }               
            
         return json_encode($response);
    }    
    function getUser($id){
        global $conn;       
        
        $response = new stdClass();
        $response->err = 0;
        $response->message = "";

        if($id == "" ){
            $response->err = 1;
            $response->message = "Soryy! Id not found in the url.";
            return json_encode($response);
        }

        $sqlSelect = "SELECT * FROM tbl_users WHERE id = $id LIMIT 1";
        $result = mysqli_query($conn, $sqlSelect);
        if($result) {   
            if(mysqli_num_rows ($result) == 1){
                $userData = mysqli_fetch_assoc($result);                        
                $response->userData = $userData; 
                $response->message =  "User Fetched Successfully!";
            }
            else
            {   
                $response->err = 1;
                $response->message =  "No record Found";
            }                   
        }else{
            $response->err = 1;
            $response->message =  "Error selecting the inserted record: " . mysqli_error($conn);
        }
        return json_encode($response);
    }
    function updateUserData($id){
        global $conn;

        $response = new stdClass();
        $response->err = 0;
        $response->message = "";

        $data = json_decode(file_get_contents("php://input"), true);         
        $email = $data['email']; 
        
        $checEmailExists = "SELECT * FROM tbl_users WHERE email = '$email'";
        $checkEmail = mysqli_query($conn, $checEmailExists);
        if(mysqli_num_rows ($checkEmail) == 1) {                    
            $response->err = 1;
            $response->message = "Sorry! User is already exist with this email.";                                     
        }else{
            $sqlupdate = "UPDATE tbl_users SET email='$email' WHERE  id = '$id'";
            $sql = "SELECT * FROM tbl_users  WHERE id = $id LIMIT 1";
            
            $updateData = mysqli_query($conn, $sqlupdate) or die("SQL Query Failed.");
            $usersData = mysqli_query($conn, $sql) or die("SQL Query Failed.");        
            
            if(mysqli_num_rows($usersData) > 0 ){
                $users = mysqli_fetch_all($usersData,MYSQLI_ASSOC);
                $response->message = "User data updated successfully!";  
                $response->users = $users; 
                header("HTTP/1.0 200 success");    
            }
        }  
            
        return json_encode($response);
    }
    function getUserList(){    
        global $conn;  
        $response = new stdClass();
        $response->err = 0;
        $response->message = "";
        
        $query = "SELECT * FROM tbl_users  ";
        $query_run = mysqli_query($conn, $query);
        
        if ($query_run) {
            if(mysqli_num_rows($query_run) > 0){
                $userData = mysqli_fetch_all($query_run, MYSQLI_ASSOC);               
                $response->message = 'Users List Fetched Successfully';
                $response->data = $userData;                
                header("HTTP/1.0 200 success");
                
            }else{
                $response->err = 1;
                $response->message = 'No Customer Found';
                header('HTTP/1.0 404 No Customer Found');              
                       
            }
        }
        return json_encode($response);
    }
    function validateUserData($data){
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];    
        $email = $data['email'];
        $phone = $data['phone'];

        $response = new stdClass();
        $response->isvalid = 0;
        $response->message = "";        

        if(!$firstName =='' &&  !$lastName=='' && !$email ==''&& !$phone ==''){
            $ePattern = "/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/";
            $nPattern = "/^[a-zA-Z]+$/";
            if (!preg_match($ePattern, $email)) {                
                $response->message = "Please enter a valid email.";
            }
            elseif(!preg_match('/^[0-9]{10}+$/', $phone)) {               
                $response->message = "Please enter a valid Phone.";
            }            
            elseif(!preg_match($nPattern, $firstName) || !preg_match($nPattern, $lastName)) {                
                $response->message = "Please enter a valid name.";               
            }else{
                $response->isvalid = 1;                
            }            
        }
        else{
            $response->message = "All fields are required.";    
         }
        return $response;
    }
?>