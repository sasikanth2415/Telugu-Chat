<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Database Connection Test</h2>";
$conn = new mysqli("sql100.infinityfree.com", "if0_38677773", "Nani901499", "if0_38677773_website");

if ($conn->connect_error) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $conn->connect_error . "</p>";
    exit;
} else {
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
}
$tables = ['messages', 'online_users', 'private_messages', 'users'];

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Table '$table' exists</p>";
        $structure = $conn->query("DESCRIBE $table");
        echo "<details><summary>Table '$table' structure:</summary><ul>";
        while ($row = $structure->fetch_assoc()) {
            echo "<li>{$row['Field']} - {$row['Type']} - {$row['Null']} - {$row['Key']}</li>";
        }
        echo "</ul></details>";
    } else {
        echo "<p style='color: red;'>❌ Table '$table' does not exist</p>";
    }
}
$test_username = "test_user";
$test_message = "Test message " . date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
$stmt->bind_param("ss", $test_username, $test_message);

if ($stmt->execute()) {
    echo "<p style='color: green;'>✅ Test message inserted successfully</p>";
    $result = $conn->query("SELECT * FROM messages ORDER BY timestamp DESC LIMIT 5");
    echo "<h3>Recent Messages:</h3><ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li><strong>{$row['username']}:</strong> {$row['message']} <small>({$row['timestamp']})</small></li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Failed to insert test message: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();

echo "<hr><p><strong>If you see all green checkmarks, your database is ready!</strong></p>";
?> 