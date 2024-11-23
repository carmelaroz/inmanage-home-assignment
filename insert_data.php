<?php
// Include the Database connection class
include('Database.php');

// Initialize the Database connection
$db = new Database('localhost', 'root', '', 'test_db');


function fetchData($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // For local testing
    $output = curl_exec($curl);

    if (curl_errno($curl)) {
        echo 'cURL Error: ' . curl_error($curl);
        curl_close($curl);
        return [];
    }

    curl_close($curl);
    return json_decode($output, true);
}

// Fetch data from JSONPlaceholder API
$users = fetchData("https://jsonplaceholder.typicode.com/users");
$posts = fetchData("https://jsonplaceholder.typicode.com/posts");

// Insert users into the database
foreach ($users as $user) {
    // Check if the user already exists
    $checkStmt = $db->conn->prepare("SELECT id FROM users WHERE id = ?");
    $checkStmt->bind_param("i", $user["id"]);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        // Insert user if not exists
        $stmt = $db->conn->prepare("INSERT INTO users (id, name, email, is_active) VALUES (?, ?, ?, 'yes')");
        $stmt->bind_param("iss", $user["id"], $user["name"], $user["email"]);
        if (!$stmt->execute()) {
            echo "Error inserting user: " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "User with id {$user["id"]} already exists. Skipping insert.<br>";
    }

    $checkStmt->close();
}

echo "Users were inserted.<br>";

// Insert posts into the database
foreach ($posts as $post) {
    // Check if the post already exists
    $checkStmt = $db->conn->prepare("SELECT post_id FROM posts WHERE post_id = ?");
    $checkStmt->bind_param("i", $post["id"]);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        // Insert post if not exists
        $stmt = $db->conn->prepare("INSERT INTO posts (post_id, user_id, title, content, is_active) VALUES (?, ?, ?, ?, 'yes')");
        $stmt->bind_param("iiss", $post["id"], $post["userId"], $post["title"], $post["body"]);
        if (!$stmt->execute()) {
            echo "Error inserting post: " . $stmt->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Post with id {$post["id"]} already exists. Skipping insert.<br>";
    }

    $checkStmt->close();
}

echo "Posts were inserted.<br>";

// Close the database connection
$db->close();
?>