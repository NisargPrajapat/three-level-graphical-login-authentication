<style>
    body,html{
        margin:0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        
    }
    header {
        /* background-color: rgb(0, 0, 120); */
        display: flex;
        justify-content: space-between; /* Distributes space between items */
        align-items: center; /* Centers items vertically */
        padding: 10px 25px; /* Adjust padding for better spacing */
        box-shadow: 10px 12px 15px rgba(255, 255, 255, 1);
        margin:25px;
        border-radius: 10px;
        border :1px solid #fff;
        min-width: 800px;
         position: absolute;
         margin-top: -620px;
         backdrop-filter: blur(1000px);
    }
    
    header .logo a {
        font-size: 1.4rem;
        padding: 5px 10px;
        border-radius: 5px;
        color: #fff;
        text-decoration: none;
    }
    
    header nav {
        display: flex;
    }
    
    header nav a {
        font-size: 1.4rem;
        padding: 5px 12px;
        border-radius: 5px;
        color: #fff;
        text-decoration: none;
        margin-left: 10px; 
        border: 1px solid #fff;
        transition: 0.5s;
    }
    
    header nav a:hover {
        background-color: rgb(255, 255, 255);
        color:rgb(11,11,69)
    }
    
            </style>
    <header>
        <div class="logo">
            <a href="#">multiSecure</a>
        </div>
        <nav class="navigation">
            <!-- <a href="/myproject/welcome.php">Home</a> -->
             <?php
            //  session_start();
                if (isset($_SESSION["loggedin"])) {
                   echo '<a href="/final/logout.php">Logout</a>';
                } else {
                    echo '<a href="/final/signup.php">Signup</a>
            <a href="/final/login.php">Login</a>';
                }
             ?>
            
            <!-- <a href="/myproject/logout.php">Logout</a> -->
            <!-- <a href="#">Contact</a> -->
        </nav>
    </header>