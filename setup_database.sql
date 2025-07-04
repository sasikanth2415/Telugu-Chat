CREATE TABLE IF NOT EXISTS messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  message TEXT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_timestamp (timestamp),
  INDEX idx_username (username)
);
CREATE TABLE IF NOT EXISTS online_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  gender VARCHAR(10),
  age INT,
  last_active DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_last_active (last_active)
);
CREATE TABLE IF NOT EXISTS private_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender VARCHAR(50) NOT NULL,
  receiver VARCHAR(50) NOT NULL,
  message TEXT NOT NULL,
  timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_sender (sender),
  INDEX idx_receiver (receiver),
  INDEX idx_timestamp (timestamp)
);
DELETE FROM online_users WHERE last_active < DATE_SUB(NOW(), INTERVAL 5 MINUTE); 