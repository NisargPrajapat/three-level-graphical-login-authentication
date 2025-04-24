<?php
include 'partials/_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    // $security_question = $_POST['security_question'];
    // $security_answer = $_POST['security_answer'];
    
    $exists = false;

    // Password validation pattern
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    if ($username == "" || $password == "" || $cpassword == "") {
        showAlert("Please fill all the details");
    } elseif (!preg_match($passwordPattern, $password)) {
        showAlert("Password must contain at least uppercase, lowercase letter, digit, and one special character, and be at least 8 characters long.");
    } else {
        $select = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($con, $select);
        $num = mysqli_num_rows($result);
        
        if ($num > 0){
            $exists = true;
            showAlert("Username Already Exists");
        } else if (($password == $cpassword) && !$exists) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            session_start();
            $_SESSION['signup'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['password1'] = $hash;
            $_SESSION['security_question'] = $security_question;
            $_SESSION['security_answer'] = $security_answer;
            header("location:signup2.php");
        } else {
            showAlert('Please enter all the details properly');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="partials/style.css">
    <style>
        .container form .form-group {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php require 'partials/_nav.php' ?>
    <div class="container">
        <h2>Signup - First Level Authentication</h2>
        <form action="" method="post">
            <div class="form-group">
                <input type="text" name="username" id="username" required placeholder="Username">
            </div>
            <!-- <div class="form-group">
                <input type="text" name="security_question" id="security_question" required placeholder="Security Question - eg. Favourite Teacher">
            </div>
            <div class="form-group">
                <input type="text" name="security_answer" id="security_answer" placeholder="Answer of Security Question - name">
            </div> -->
            <div class="form-group">
                <input type="password" name="password" id="password" required placeholder="Password">
            </div>
            <div class="form-group">
                <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm Password"><br>
                <small>Make sure to type the same password</small>
            </div>
            <br>
            <button type="submit" id="signupBtn">Next Level</button>
        </form>
        <div class="register-link">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
