<?php
session_start();
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $response['success'] = false;
        $input = json_decode(file_get_contents("php://input"),true);
        $password2 = $input['password'];
        $filename = $input['fileName'];
        // $_SESSION['password2'] = $password2;
        // echo $password2;
        // print_r($_SESSION);
        // die();
        $_SESSION['password2'] = $password2;
        // print_r($_SESSION);
        // die();
        // echo $filename;
        // echo $password2;
        // print_r($_SESSION);
        $response['success'] = true;
        $response['message'] = 'Images saved successfully';
        echo json_encode($response);
    }
?>