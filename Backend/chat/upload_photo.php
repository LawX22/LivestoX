<?php
include('../../Backend/db/db_connect.php');

$file = $_FILES['img']; // Now we are handling only one file

try {
    // Check if the file was uploaded without errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $file['error']);
    }

    // Generate a unique filename based on the current timestamp and original filename
    $filename = time() . '_' . basename($file["name"]);

    // Specify the target directory for saving the image
    $target_dir = "../../uploads/livestock_posts/";

    // Ensure the target directory exists
    if (!is_dir($target_dir)) {
        throw new Exception("Target directory does not exist.");
    }

    // Construct the full path to save the uploaded file
    $target_file = $target_dir . $filename;

    // Check if the file is a valid image (can be expanded for additional formats)
    $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($imageFileType, $allowedTypes)) {
        throw new Exception("Only JPG, JPEG, PNG, and GIF files are allowed.");
    }

    // Move the uploaded file to the target directory
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        throw new Exception("Error moving uploaded file.");
    }

    echo json_encode(['res' => 'success', 'filename' => basename($filename)]);
} catch (Exception $e) {
    // Catch any exceptions and return the error message
    echo json_encode(['error' => $e->getMessage()]);
}
?>
