<?php   
$server = 'localhost';
 $user = 'root';
 $password='';
 $db = 'users';

 $con = mysqli_connect($server,$user,$password,$db);

 if(!$con){
    echo "Error in connecting ".mysqli_connect_error();
 }

 function showAlert($message){
    echo "<script>alert(`$message`)</script>";
 }
?>