<?php

$imageUrl = "https://cdn2.vectorstock.com/i/1000x1000/23/81/default-avatar-profile-icon-vector-18942381.jpg";

$savePath = "icon.jpg";

try {
    $imageData = file_get_contents($imageUrl);

    if ($imageData === false) {
        throw new Exception("Failed to fetch the image from the URL.");
    }

    $saveResult = file_put_contents($savePath, $imageData);

    if ($saveResult === false) {
        throw new Exception("Failed to save the image to the server.");
    }

    echo "Image saved successfully as '$savePath'.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>