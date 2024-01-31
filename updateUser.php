<?php
    header('Access-Control-Allow-Origin:*');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Method: PUT');

    include('function.php');

    $requestMethod = $_SERVER["REQUEST_METHOD"];
    if($requestMethod == "PUT" &&  isset($_GET['id'])){         
        $updatedUser = updateUserData($_GET['id']);
        echo $updatedUser;         
    }
    else
    {
        $response = new stdClass();
        $response->status = 405;
        $response->message = $requestMethod. ' Method Not Allowed';       
        header('HTTP/1.0 405  Method Not Allowed');

        echo json_encode ($response);
    }
?>