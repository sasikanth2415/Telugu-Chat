<?php
session_start();
$username = $_POST['username'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$_SESSION['guest'] = [
    'username' => $username,
    'gender' => $gender,
    'age' => $age
];
header("Location: talkroom.php");
exit;
?>
