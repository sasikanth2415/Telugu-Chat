<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_SESSION['username'] ?? ($_SESSION['guest']['username'] ?? null);
$gender = $_SESSION['gender'] ?? ($_SESSION['guest']['gender'] ?? 'Other');
$age = $_SESSION['age'] ?? ($_SESSION['guest']['age'] ?? null);

if (!$username) {
  header("Location: login.html");
  exit;
}

$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");
if (!$conn->connect_error) {
  $stmt = $conn->prepare("REPLACE INTO online_users (username, gender, age, last_active) VALUES (?, ?, ?, NOW())");
  $stmt->bind_param("ssi", $username, $gender, $age);
  $stmt->execute();
  $stmt->close();
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TeluguChat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="talkroom.css">
</head>
<body>
  <div class="chat-app">
    <header>
      <div class="header-left">
        <span class="menu-icon">&#9776;</span>
        <span class="logo">TeluguChat</span>
      </div>
      <div class="header-right">
        <span class="icon" id="refreshBtn" title="Refresh">&#8635;</span>
        <span class="icon" id="profileBtn" title="Profile">&#128100;</span>
      </div>
    </header>

    <div class="main">
      <div class="chat-section">
        <div class="chat-messages" id="chatMessages"></div>
        <div class="chat-input">
          <input type="text" id="chatInput" placeholder="Type here...">
          <button id="sendBtn">&#10148;</button>
        </div>
      </div>

      <aside class="sidebar">
        <div class="sidebar-header">
          <span>Online <span class="online-count" id="onlineCount">0</span></span>
        </div>
        <ul class="user-list" id="userList"></ul>
      </aside>
    </div>
  </div>
  <div id="profileModal" class="modal">
    <div class="modal-content">
      <span class="close" id="closeProfile">&times;</span>
      <h2>Your Profile</h2>
      <label for="displayName">Display Name:</label>
      <input type="text" id="displayName" maxlength="20">
      <button id="saveProfile">Save</button>
    </div>
  </div>
  <div id="privateChat" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" id="closePrivateChat">&times;</span>
      <h2>Chat with <span id="chatWithUser"></span></h2>
      <div id="privateMessages" style="height:200px; overflow-y:auto; border:1px solid #ccc; padding:5px;"></div>
      <input type="text" id="privateInput" placeholder="Type a message...">
      <button id="privateSendBtn">Send</button>
    </div>
  </div>

  <script src="talkroom1.js" defer></script>
</body>
</html>
