<?php
include 'partials/_dbconnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false];
    $pattern = $_POST['pattern'];

    if (!isset($_SESSION['resizedImage'], $_SESSION['username'], $_SESSION['password1'], $_SESSION['password2'])) {
        echo json_encode(['error' => 'Session data missing']);
        exit();
    }

    $resizedImageData = $_SESSION['resizedImage'];
    $resizedImageData = str_replace('data:image/jpeg;base64,', '', $resizedImageData);
    $resizedImageData = base64_decode($resizedImageData);

    $username = $_SESSION['username'];
    $password = $_SESSION['password1'];
    $gridSize = $_SESSION['gridSize'];
    $password2 = $_SESSION['password2'];
    // $security_question = $_SESSION['security_question'];
    // $security_answer = $_SESSION['security_answer'];

    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode(['error' => 'Username already exists']);
        exit();
    }

    $resizedFileName = time() . '.jpg';
    file_put_contents('partials/' . $resizedFileName, $resizedImageData);
    $response['resizedImage'] = $resizedFileName;
    $resizedFileName = $resizedFileName . $_SESSION['gridSize'];

    // $stmt = $con->prepare("INSERT INTO users (username, password, password2, image_name, password3,security_question,security_answer) VALUES (?, ?, ?, ?,?, ?, ?)");
    $stmt = $con->prepare("INSERT INTO users (username, password, password2, image_name, password3) VALUES (?, ?, ?, ?,?)");
    // $stmt->bind_param("sssssss", $username, $password, $password2, $resizedFileName, $pattern,$security_question, $security_answer);
    $stmt->bind_param("sssss", $username, $password, $password2, $resizedFileName, $pattern);
    if ($stmt->execute()) {
        $_SESSION['loggedin'] == true;
        $response['success'] = true;
        $response['message'] = 'Your account has been created';
        echo json_encode($response);
        exit;
    } else {
        $response['error'] = 'Database insertion failed';
        echo json_encode($response);
        exit;
    }

    echo json_encode($response);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
