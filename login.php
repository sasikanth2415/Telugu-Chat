<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$stmt = $conn->prepare("SELECT id, password, gender, age FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 1) {
    $stmt->bind_result($id, $hashed, $gender, $age);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['gender'] = $gender;
        $_SESSION['age'] = $age;

        header("Location: talkroom.php");
        exit;
    } else {
        echo "❌ Invalid password. <a href='login.html'>Try again</a>";
    }
} else {
    echo "❌ User not found. <a href='login.html'>Try again</a>";
}

$stmt->close();
$conn->close();
?>
