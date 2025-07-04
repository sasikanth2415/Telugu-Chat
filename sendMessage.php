<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header("Content-Type: application/json");

$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$username = $_SESSION['username'] ?? ($_SESSION['guest']['username'] ?? null);

if (!$username) {
    http_response_code(401);
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid message data"]);
    exit;
}

$message = trim($data['message']);

if (empty($message)) {
    http_response_code(400);
    echo json_encode(["error" => "Message cannot be empty"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (username, message, timestamp) VALUES (?, ?, NOW())");
$stmt->bind_param("ss", $username, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Message sent successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save message: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
