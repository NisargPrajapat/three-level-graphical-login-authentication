
<?php
    session_start();
    include 'partials/_dbconnect.php';
    // if(!isset($_SESSION['username']) || !isset($_SESSION['password1']) || !isset($_SESSION['password2'])){
    //     header('location:login2.php');
    // }

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] === false) {
    header('Location: login2.php');
    exit();
}
?>

<?php 
 if (isset($_POST['login']) == "POST") {
    $pattern = $_POST['pattern'];
    // print_r($_SESSION);
    $username = $_SESSION['username'];
    $select = "select * from users where username = '$username'";
    $result = mysqli_query($con,$select);
    if($row = mysqli_fetch_assoc($result)){
        // echo $row['password3'];
        if($row['password3'] == $pattern) {
            $_SESSION['password3'] = true;
            header('location:welcome.php');
        } else {
            showAlert("Passwod not match");
        }
    }
    // echo $select;
    // echo $pattern;
 }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Graphical Password Login</title>
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(5, 70px);
            grid-gap: 40px;
            justify-content: center;
            margin-top: 50px;
            position: relative;
        }
        .dot {
            width: 50px;
            height: 50px;
            background-color: #ccc;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
        }
        .dot.selected {
            background-color: red;
        }
        .line {
            position: absolute;
            height: 2px;
            background-color: red;
            transform-origin: 0 0;
        }
        button{
            margin-top: 60px;
        } 
        p{
            margin: 10px;
            text-align: center;
        }
    </style>
<link rel="stylesheet" href="partials/style.css">

</head>
<body>
    <form id="signupForm" action="" method="POST">
        <h1>Login with Graphical Password</h1>
        <p>Connect dots to make your pattern</p>
        <!-- <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br> -->
        <div class="grid" id="grid">
            <!-- Dots will be added here by JavaScript -->
        </div>
        <input type="hidden" id="pattern" name="pattern">
        <button type="submit" name="login">Login</button>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const grid = document.getElementById('grid');
            const patternInput = document.getElementById('pattern');
            let pattern = [];
            let isDrawing = false;
            let lastDot = null;

            // Create dots
            for (let i = 0; i < 25; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot';
                dot.dataset.index = i;
                grid.appendChild(dot);

                dot.addEventListener('mousedown', (event) => {
                    isDrawing = true;
                    selectDot(dot, event);
                });

                dot.addEventListener('mouseover', (event) => {
                    if (isDrawing) {
                        selectDot(dot, event);
                    }
                });
            }

            document.addEventListener('mouseup', () => {
                isDrawing = false;
                patternInput.value = JSON.stringify(pattern);

            });

            function selectDot(dot, event) {
                const index = parseInt(dot.dataset.index);
                if (!pattern.includes(index)) {
                    dot.classList.add('selected');
                    pattern.push(index);
                    // console.log(pattern.join());

                    if (lastDot) {
                        drawLine(lastDot, dot);
                    }

                    lastDot = dot;
                }
            }

            function drawLine(dot1, dot2) {
                const rect1 = dot1.getBoundingClientRect();
                const rect2 = dot2.getBoundingClientRect();
                const x1 = rect1.left + rect1.width / 2;
                const y1 = rect1.top + rect1.height / 2;
                const x2 = rect2.left + rect2.width / 2;
                const y2 = rect2.top + rect2.height / 2;

                const line = document.createElement('div');
                line.className = 'line';

                const length = Math.sqrt((x2 - x1) ** 2 + (y2 - y1) ** 2);
                line.style.width = `${length}px`;

                const angle = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;
                line.style.transform = `rotate(${angle}deg)`;

                line.style.left = `${x1 - grid.getBoundingClientRect().left}px`;
                line.style.top = `${y1 - grid.getBoundingClientRect().top}px`;

                grid.appendChild(line);
            }
            // document.getElementById('signupForm').addEventListener('submit', function(event) {
            //     event.preventDefault();
            //     const formData = new FormData(this);
            //     fetch('save.php', {
            //         method: 'POST',
            //         body: formData
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             alert(data.message);
            //             window.location.href = 'welcome.php';
            //         } else {
            //             alert(data.error);
            //         window.location.href = 'login.php';

            //         }
            //     })
            //     .catch(error => {
            //         console.error('Error:', error);
            //     });
            // });
        });

    </script>
</body>
</html>
