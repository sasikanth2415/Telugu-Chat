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

$sender = $_SESSION['username'] ?? ($_SESSION['guest']['username'] ?? null);
$receiver = $_POST['receiver'] ?? '';
$message = trim($_POST['message'] ?? '');

if (!$sender || !$receiver || !$message) {
    http_response_code(400);
    echo json_encode(["error" => "Missing sender, receiver, or message"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO private_messages (sender, receiver, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $sender, $receiver, $message);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Failed to save private message"]);
}

$stmt->close();
$conn->close();
?> 