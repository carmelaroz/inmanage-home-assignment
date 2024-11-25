/**
 Test of photo validation - to ensure that the file saving was successful, verify that the file exists and verify that the file size is greater than 0.
**/

<?php
function test_fetch_photo() {
    $expectedFileName = "icon.jpg";

    // Execute the file and capture the output
    ob_start(); // Start output buffering
    include '../src/fetch_photo.php'; // Path to the file
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

?>