<?php
session_start();
$currentUser = $_SESSION['username'] ?? ($_SESSION['guest']['username'] ?? null);
$withUser = $_GET['with'] ?? '';

if (!$currentUser || !$withUser) {
    exit("[]");
}

$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "f0_38677773_website");
if ($conn->connect_error) exit("[]");

$stmt = $conn->prepare("
    SELECT sender, message, timestamp
    FROM private_messages
    WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?)
    ORDER BY timestamp ASC
");
$stmt->bind_param("ssss", $currentUser, $withUser, $withUser, $currentUser);
$stmt->execute();

$result = $stmt->get_result();
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

header("Content-Type: application/json");
echo json_encode($messages);
?>
