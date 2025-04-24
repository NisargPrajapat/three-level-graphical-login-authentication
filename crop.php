<?php
session_start();
include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine if it's a signup or login process
    $isSignup = isset($_FILES['image']);
    $imagePath = $isSignup ? $_FILES['image']['tmp_name'] : null;
    $gridSize = $_POST['size'];
    $_SESSION['gridSize'] = $gridSize;
    $username = $_SESSION['username'];

    // During login, fetch image path from database
    if (!$isSignup) {
        $select = "SELECT image_name FROM users WHERE username = ?";
        $stmt = $con->prepare($select);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $image =  $row['image_name'];
            $imagePath = 'partials/' . substr($image, 0, -1);

        } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Image file not found for user']);
                    exit;
                }
    }

    if (!file_exists($imagePath)) {
        http_response_code(400);
        echo json_encode(['error' => 'Image file does not exist']);
        exit;
    }

    // Get the image type
    $imageType = exif_imagetype($imagePath);
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($imagePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($imagePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($imagePath);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unsupported image type']);
            exit;
    }

    list($width, $height) = getimagesize($imagePath);
    $nwidth = 400;
    $nheight = 400;
    $newImage = imagecreatetruecolor($nwidth, $nheight);

    // Resize the image
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);

    // Output the resized image to a string
    ob_start();
    imagejpeg($newImage);
    $resizedImageData = ob_get_clean();
    imagedestroy($newImage);
    imagedestroy($source);

    $image = imagecreatefromstring($resizedImageData);

    if ($image === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid image file']);
        exit;
    }

    $imageWidth = imagesx($image);
    $imageHeight = imagesy($image);
    $cropWidth = ($imageWidth / $gridSize);
    $cropHeight = ($imageHeight / $gridSize);

    $croppedImages = [];

    for ($row = 0; $row < $gridSize; $row++) {
        for ($col = 0; $col < $gridSize; $col++) {
            $x = $col * $cropWidth;
            $y = $row * $cropHeight;

            if ($col == $gridSize - 1) {
                $cropWidth = $imageWidth - $x;
            }
            if ($row == $gridSize - 1) {
                $cropHeight = $imageHeight - $y;
            }

            $croppedImage = imagecreatetruecolor($cropWidth, $cropHeight);
            imagecopyresampled(
                $croppedImage,
                $image,
                0, 0,
                $x, $y,
                $cropWidth, $cropHeight,
                $cropWidth, $cropHeight
            );

            ob_start();
            imagejpeg($croppedImage);
            $imageData = ob_get_clean();
            imagedestroy($croppedImage);

            $croppedImages[] = 'data:image/jpeg;base64,' . base64_encode($imageData);
        }
    }

    imagedestroy($image);

    $_SESSION['resizedImage'] = 'data:image/jpeg;base64,' . base64_encode($resizedImageData);
    $_SESSION['croppedImages'] = $croppedImages;

    echo json_encode(['resizedImage' => $_SESSION['resizedImage'], 'croppedImages' => $croppedImages, 'fileName' => basename($imagePath)]);
    exit;
}

http_response_code(400);
echo json_encode(['error' => 'Invalid request']);
?>
