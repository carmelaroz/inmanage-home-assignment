/**
 Test 1: photo validation - to ensure that the file saving was successful, 
         verify that the file exists and verify that the file size is greater than 0.
 Test 2: Query validation - to verify SQL queries produce expected results.
*/

<?php
function test_fetch_photo() {
    $expectedFileName = "icon.jpg";

    // Execute the file and capture the output
    ob_start(); // Start output buffering
    include 'fetch_photo.php'; // Path to the file
    $output = ob_get_clean(); // Get and clear the output buffer

    // Check if the file saving was successful
    $expectedSuccessMessage = "Image saved successfully as '$expectedFileName'.";
    if (trim($output) === $expectedSuccessMessage) {
        echo "Test Passed: Image saved successfully.\n";
    } else {
        echo "Test Failed: Expected '$expectedSuccessMessage' but got '$output'.\n";
    }

    // Verify that the file exists
    if (file_exists($expectedFileName)) {
        echo "Test Passed: File '$expectedFileName' exists.\n";
    } else {
        echo "Test Failed: File '$expectedFileName' does not exist.\n";
    }

    // Verify the file size is greater than 0
    if (file_exists($expectedFileName) && filesize($expectedFileName) > 0) {
        echo "Test Passed: File '$expectedFileName' contains data.\n";
    } else {
        echo "Test Failed: File '$expectedFileName' is empty or not saved correctly.\n";
    }
}

test_fetch_photo();

function test_posts_per_hour_query($conn) {
    // Set up test data
    $conn->query("TRUNCATE TABLE posts"); // Clear the posts table
    $conn->query("TRUNCATE TABLE posts_per_hour"); // Clear the posts_per_hour table

    // Insert test data into the posts table
    $conn->query("INSERT INTO posts (user_id, title, content, created_date) VALUES 
        (1, 'Post 1', 'Content 1', '2024-11-25 10:15:00'),
        (2, 'Post 2', 'Content 2', '2024-11-25 10:45:00'),
        (3, 'Post 3', 'Content 3', '2024-11-25 11:05:00')");

    // Run the query to populate posts_per_hour
    $query = "
        INSERT INTO posts_per_hour (post_date, post_hour, post_count)
        SELECT 
            DATE(created_date) AS post_date,
            TIME_FORMAT(CONCAT(HOUR(created_date), ':00:00'), '%H:%i:%s') AS post_hour,
            COUNT(*) AS post_count
        FROM posts
        GROUP BY post_date, post_hour
    ";
    $conn->query($query);

    // Fetch and verify results from posts_per_hour
    $result = $conn->query("SELECT * FROM posts_per_hour ORDER BY post_date, post_hour");
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // Expected results
    $expected = [
        ['post_date' => '2024-11-25', 'post_hour' => '10:00:00', 'post_count' => 2],
        ['post_date' => '2024-11-25', 'post_hour' => '11:00:00', 'post_count' => 1]
    ];

    // Check if the data matches the expected results
    if ($data == $expected) {
        echo "Test Passed: Posts per hour query returned correct results.\n";
    } else {
        echo "Test Failed: Expected " . json_encode($expected) . " but got " . json_encode($data) . ".\n";
    }
}

$conn = new mysqli("localhost", "root", "", "test_db");
test_posts_per_hour_query($conn);

?>