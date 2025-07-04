<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$servername = "sql100.infinityfree.com";
$db_username = "if0_38677773";
$db_password = "Nani901499";
$database   = "if0_38677773_website";
$conn = new mysqli($servername, $db_username, $db_password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$username = trim($_POST['username']);
$email    = trim($_POST['email']);
$password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
$gender   = $_POST['gender'];
$age      = intval($_POST['age']); 
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "⚠️ Username already exists. <a href='register.html'>Try again</a>";
    exit;
}
$stmt->close();
$stmt = $conn->prepare("INSERT INTO users (username, email, password, gender, age) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $username, $email, $password, $gender, $age);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['gender']   = $gender;
    $_SESSION['age']      = $age;
    header("Location: talkroom.php");
    exit;
} else {
    echo "Error registering: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
