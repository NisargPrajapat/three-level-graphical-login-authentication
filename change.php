<?php
    $select = "select secu"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
<link rel="stylesheet" href="partials/style.css">
</head>
<body>
    
    <?php require 'partials/_nav.php' ?>
        
    <div class="container">
        <form action="" method="post">
            <h1>Answer the Security Question</h1>
            <div class="form-group">
                <!-- <label for="username">Username</label>
                <br> -->
                
                <input type="text" name="security_question" id="security_question"  readonly>
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
            <p class=""><a href="change.php">Forget Password</a></p>
                <p class="">Don`t have an account? <a href="signup.php">Register</a></p>
            </div>
    </div>
</body>
</html>