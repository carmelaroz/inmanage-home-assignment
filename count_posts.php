<?php

require_once 'Database.php';
$db = new Database('localhost', 'root', '', 'test_db');

// Check if the connection is successful
if ($db->conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count posts per hour
$query = "
    SELECT 
        DATE(created_date) AS post_date,
        HOUR(created_date) AS post_hour,
        COUNT(*) AS post_count
    FROM 
        posts
    GROUP BY 
        post_date, post_hour
    ORDER BY 
        post_date, post_hour;
";

$result = $db->conn->query($query);

if ($result->num_rows > 0) {
    $insert_query = $db->conn->prepare("
        INSERT INTO posts_per_hour (post_date, post_hour, post_count) 
        VALUES (?, ?, ?)
    ");

    $insert_query->bind_param("sii", $post_date, $post_hour, $post_count);

    while ($row = $result->fetch_assoc()) {
        $post_date = $row['post_date'];
        $post_hour = $row['post_hour'];
        $post_count = $row['post_count'];
        
        $insert_query->execute();
    }

    echo "Data inserted successfully.";
} else {
    echo "No data found.";
}

$db->conn->close();

?>
