<?php
require_once 'Database.php';
$db = new Database('localhost', 'root', '', 'test_db');

// Create users table
$create_users_table = "
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    is_active ENUM('yes', 'no') DEFAULT 'yes'
)";

// Create posts table
$create_posts_table = "
CREATE TABLE IF NOT EXISTS posts (
    post_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255),
    content TEXT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_active ENUM('yes', 'no') DEFAULT 'yes'
)";

$create_count_posts_table = "
CREATE TABLE IF NOT EXISTS posts_per_hour (
    post_date DATE NOT NULL,
    post_hour TIME NOT NULL,
    post_count INT NOT NULL,
    PRIMARY KEY (post_date, post_hour)
)";

// Execute queries
if ($db->conn->query($create_users_table) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $db->conn->error . "<br>";
}

if ($db->conn->query($create_posts_table) === TRUE) {
    echo "Posts table created successfully<br>";
} else {
    echo "Error creating posts table: " . $db->conn->error . "<br>";
}

if ($db->conn->query($create_count_posts_table) === TRUE) {
    echo "count posts table created successfully<br>";
} else {
    echo "Error creating posts table: " . $db->conn->error . "<br>";
}

// Close connection
$db->conn->close();
?>