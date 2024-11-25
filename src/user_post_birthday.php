<?php
require_once 'Database.php';

try {
    $db = new Database('localhost', 'root', '', 'test_db');

    // SQL query to get the last post of users with birthdays this month
    $query = "
        SELECT 
            users.id AS user_id,
            users.name AS user_name,
            users.email AS user_email,
            users.birthday AS user_birthday,
            posts.post_id AS post_id,
            posts.title AS post_title,
            posts.content AS post_content,
            posts.created_date AS post_date
        FROM 
            users
        JOIN 
            posts
        ON 
            users.id = posts.user_id
        JOIN 
            (
                SELECT 
                    user_id, 
                    MAX(created_date) AS latest_post_date
                FROM 
                    posts
                GROUP BY 
                    user_id
            ) latest_posts
        ON 
            posts.user_id = latest_posts.user_id 
            AND posts.created_date = latest_posts.latest_post_date
        WHERE 
            MONTH(users.birthday) = MONTH(CURRENT_DATE)
        ORDER BY 
            users.id;
    ";

    $results = $db->select($query);

    if (!empty($results)) {
        echo "<div style='border: 1px solid #ddd; padding: 15px; margin-bottom: 10px;'>";

        echo "<h2>Users with birthdays this month and their last post:</h2>";
        foreach ($results as $row) {
            echo "<strong>User:</strong> " . $row['user_name'] . " (" . $row['user_email'] . ")<br>";
            echo "<strong>Birthday:</strong> " . $row['user_birthday'] . "<br>";
            echo "<strong>Last Post:</strong> " . $row['post_title'] . "<br>";
            echo "<strong>Content:</strong> " . $row['post_content'] . "<br>";
            echo "<strong>Posted on:</strong> " . $row['post_date'] . "<br><br>";
        }
    } else {
        echo "No users with birthdays this month have made posts.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
