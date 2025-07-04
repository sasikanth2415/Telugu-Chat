<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");
session_start();

$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB error"]);
    exit;
}

$result = $conn->query("SELECT username, message, timestamp FROM messages ORDER BY timestamp DESC LIMIT 50");
$messages = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    $messages = array_reverse($messages);
} else {
    http_response_code(response_code: 500);
    echo json_encode(["error" => "Failed to fetch messages"]);
    exit;
}

echo json_encode($messages);
$conn->close();
?>
