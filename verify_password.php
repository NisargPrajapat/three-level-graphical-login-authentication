<?php
session_start();
include 'partials/_dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $inputPassword = $data['password'];
    $fileName = $data['fileName'];
    // $inputSize = $data['inputSize'];
    $username = $_SESSION['username'];

    // Fetch the stored password for the user
    $query = "SELECT password2 FROM users WHERE username = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();
    
    // Debugging statements
    error_log("Username: $username");
    error_log("Stored Password: $storedPassword");
    error_log("Input Password: $inputPassword");
    error_log("Grid Size (session): " . $_SESSION['gridSize']);
    // error_log("Grid Size (input): $inputSize");
    
    
    if ($_SESSION['dbGrid'] == $_SESSION['gridSize']) {
        if ($inputPassword === $storedPassword) {
            $_SESSION['authenticated'] = true;
            echo json_encode(['success' => true]);
        } else{
            $_SESSION['authenticated'] = false;
        echo json_encode(['success' => false]);
        exit;
        }
    }
     else {
        $_SESSION['authenticated'] = false;
        echo json_encode(['success' => false]);
    }
    exit;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}
?>
