<!DOCTYPE html>
<html>
<head>
  <title>Contact Us | HoneyChat+</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="welcome">
  <div class="welcome-box">
    <h2>Contact Us</h2>
    <form method="post" action="">
      <input type="text" name="name" placeholder="Your Name" required><br><br>
      <input type="email" name="email" placeholder="Your Email" required><br><br>
      <textarea name="message" placeholder="Your Message" rows="4" required></textarea><br><br>
      <button type="submit" class="btn">Send</button>
    </form>
    <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<p>Thank you for contacting us! We'll reply soon.</p>";
      }
    ?>
    <a href="home.php" class="btn">Back to Home</a>
  </div>
</body>
</html>
