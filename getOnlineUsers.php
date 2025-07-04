<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");
session_start();

$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}
$conn->query("DELETE FROM online_users WHERE last_active < NOW() - INTERVAL 60 SECOND");

$result = $conn->query("SELECT username, gender, age FROM online_users ORDER BY last_active DESC");
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
