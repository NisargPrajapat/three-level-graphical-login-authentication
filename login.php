<?php
include 'partials/_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $exists = false;
    // $select = "select * from users where username = '$username' and password = '$password'";
    $select = "select * from users where username = '$username'";
    $login = false;
    $result = mysqli_query($con,$select);
    $num = mysqli_num_rows($result);
    if ($num == 1){
        while ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password,$row['password'])) {
                $login=true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['password1'] = $password;
                header('location:login2.php');
                // showAlert("Successful Login");
            }
            else {
                showAlert('Invalid Credentials');
            }
        }
    }else {
        showAlert('Invalid User or Passsword');
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-1</title>
<link rel="stylesheet" href="partials/style.css">
</head>
<body>
    
    <?php require 'partials/_nav.php' ?>
        
    <div class="container">
        <form action="" method="post">
            <h1>Login - Level 1</h1>
            <div class="form-group">
                <!-- <label for="username">Username</label>
                <br> -->
                <input type="username" name="username" id="username" placeholder="Username" required>
                <i class="bx bxs-user"></i>
            </div>
            <div class="form-group">
                <!-- <label for="password">Password</label>
                <br> -->
                <input type="password" name="password" id="password" placeholder="Passsword" required>
                <i class="bx bxs-lock-alt"></i>
            </div>
            
            <button type="submit">Next</button>
            </form>
            <div class="register-link">
            <p class=""><a href="">Forget Password</a></p>
                <p class="">Don`t have an account? <a href="signup.php">Register</a></p>
            </div>
    </div>
</body>
</html>