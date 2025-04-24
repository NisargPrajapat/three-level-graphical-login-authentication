<?php
session_start();

    // if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true){
    //     header('location:login.php');
    // }
    if(!isset($_SESSION['username'],$_SESSION['password3'])) {
        header('location:login.php');
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="partials/style.css">
</head>
<body>
    <?php include 'partials/_nav.php' ?>
    <div class="container">
        <h1>  Welcome <?php echo $_SESSION['username'];?></h1>
        <h2>You have Scuccessfully logged in</h2>
    </div>
</body>
</html>