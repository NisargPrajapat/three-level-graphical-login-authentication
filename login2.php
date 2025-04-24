<?php
session_start();
include 'partials/_dbconnect.php';

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
//     header('Location: login3.php');
//     exit();
// }

$username = $_SESSION['username'];
$select = "SELECT image_name FROM users WHERE username = ?";
$stmt = $con->prepare($select);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $image_info = $row['image_name'];
    $gridSize = substr($image_info, -1);
    $nam = substr($image_info, 0, strlen($image_info) - 1);
    $image_name = 'partials/' . $nam;
    $_SESSION['dbGrid'] = $gridSize;


    if (!file_exists($image_name)) {
        echo "Image file does not exist: " . $image_name;
        exit();
    }
} else {
    echo "No image found for user.";
    exit();
}
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - 2</title>
    <style>
        body {
            /* font-family: Arial, sans-serif; */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* h1 {
            margin-top: 20px;
        } */
        #container {
            display: flex;
            width: 80%;
            margin-top: 20px;
        }

        #left-pane,
        #right-pane {
            flex: 1;
            margin: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            /* backdrop-filter: blur(300px); */
            width: 500px;
            height: 500px;
        }

        #preview {
            /* display: ; */
            margin-bottom: 10px;
            text-align: center;
            width: 400px;
            height: 400px;
        }

        #preview img {
            /* max-width: 100%;
            height: 100%; */
            object-fit: contain;
        }

        #croppedImages {

            /* gap: 1px; */
            /* margin-top: 10px; */
            /* width: 400px; */
            height: 400px;
        }

        .cropped-image {
            /* width: 100px;
            height: 100px; */
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .cropped-image.selected {
            border: 2px solid red;
        }

        .button-container {
            display: flex;
            /* text-align: center; */
            margin-top: 20px;
        }

        button {
            margin: 10px;
            padding: 0 20px;
            /* align-items: center;
            text-align: center; */
        }

        #imagePreview {
            width: 400px;
            height: 350px;
        }

        h3 {
            text-align: center;
        }

        #imageInput {
            margin-top: -30px;
            position: absolute;
        }
    </style>
    <link rel="stylesheet" href="partials/style.css">
</head>
<body>
    <h1>Login - Level 2 (Select the Image which is Selected during Login)</h1>
    <div id="container">
        <div id="left-pane">
            <form id="uploadForm" enctype="multipart/form-data">

                <input type="hidden" id="imagePath" name="imagePath" value="<?php echo $image_name; ?>">
                <h3>Preview:</h3>
                <div id="preview">
                    <img id="imagePreview" src="<?php echo $image_name; ?>" alt="Image Preview" height="400px" />
                </div>
                <!-- <div class="button-container">
                    <button type="button" id="cropButton">Crop Image</button>
                </div> -->
                <select name="size" id="size">
                    <option value="3">3 X 3</option>
                    <option value="4">4 X 4</option>
                    <option value="5">5 X 5</option>
                    <option value="6">6 X 6</option>
                </select>
                <input type="text" name="filename" id="filename" value="" readonly placeholder="readonly">
            </form>
        </div>
        <div id="right-pane">
            <h3>Cropped Images:</h3>
            <div id="croppedImages"></div>
        </div>
    </div>
    <div class="button-container">
        <button type="button" id="cropButton">Crop Image</button>

        <button type="button" id="generatePasswordButton">Generate Password</button>
        <p id="generatedPassword"></p>
    </div>

    <script>

        document.getElementById('cropButton').addEventListener('click', function () {
            const formData = new FormData(document.getElementById('uploadForm'));
            const imagePath = document.getElementById('imagePath').value;

            if (!imagePath) {
                alert("Upload image");
                return;
            }

            fetch('crop.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const gridSize = document.getElementById("size").value;
                    const croppedImagesContainer = document.getElementById('croppedImages');

                    croppedImagesContainer.style.display = 'grid';
                    croppedImagesContainer.style.gridTemplateColumns = `repeat(${gridSize}, 1fr)`;
                    croppedImagesContainer.innerHTML = '';

                    const selectedImages = [];
                    data.croppedImages.forEach((image, index) => {
                        const imgElement = document.createElement('img');
                        imgElement.src = image;
                        imgElement.className = 'cropped-image';
                        imgElement.dataset.index = index;
                        imgElement.addEventListener('click', function () {
                            if (this.classList.contains('selected')) {
                                this.classList.remove('selected');
                                const imgIndex = selectedImages.indexOf(this.dataset.index);
                                if (imgIndex > -1) {
                                    selectedImages.splice(imgIndex, 1);
                                }
                            } else {
                                this.classList.add('selected');
                                selectedImages.push(this.dataset.index);
                            }
                        });
                        croppedImagesContainer.appendChild(imgElement);
                    });
                    document.getElementById("filename").value = data.fileName;

                    document.getElementById('generatePasswordButton').addEventListener('click', function () {
                        if (selectedImages.length < 4) {
                            alert("Please select at least 4 grids");
                        } else {
                            // Join the selected images' indices in the order they were clicked
                            const password = selectedImages.join('');
                            const fileName = document.getElementById("filename").value;
                            // document.getElementById('generatedPassword').textContent = `Generated Password: ${password}`;
                            // const inputSize = document.getElementById('size').value;

                            // Send the password to the server for verification
                            fetch('verify_password.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ password, fileName })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Debugging statement
                                       // console.log('Password verification successful');
                                        alert("Password successfully verified");
                                        window.location.href = 'login3.php';
                                    } else {
                                        console.log('Password verification failed');
                                        alert("Invalid credentials");
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        }
                    });


                })
                .catch(error => console.error('Error:', error));
        });

    </script>
</body>

</html>