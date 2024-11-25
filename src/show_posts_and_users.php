<?php

require_once 'Database.php';

$db = new Database('localhost', 'root', '', 'test_db');

$query = "
    SELECT 
        u.id AS user_id,
        u.name AS user_name,
        u.email AS user_email,
        p.title AS post_title,
        p.content AS post_content
    FROM 
        users u
    JOIN 
        posts p
    ON 
        user_id = p.user_id
    WHERE 
        u.is_active = 'yes' 
        AND p.is_active = 'yes';
";

try {
    $results = $db->select($query);

    if (!empty($results)) {
        echo "<div style='font-family: Arial, sans-serif;'>";
        foreach ($results as $row) {
            echo "<div style='border: 1px solid #ddd; padding: 15px; margin-bottom: 10px;'>";
            echo "<div style='display: flex; align-items: center;'>";
            echo "<img src='icon.jpg' alt='User Avatar' style='width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;'>";
            echo "<div>";
            echo "<strong>" . htmlspecialchars($row['user_name']) . "</strong><br>";
            echo "<small>Email: " . htmlspecialchars($row['user_email']) . "</small>";
            echo "</div>";
            echo "</div>"; // End of user header

            echo "<div style='margin-top: 10px;'>";
            echo "<h4 style='margin: 0;'>" . htmlspecialchars($row['post_title']) . "</h4>";
            echo "<p>" . nl2br(htmlspecialchars($row['post_content'])) . "</p>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "No active users or posts found.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
