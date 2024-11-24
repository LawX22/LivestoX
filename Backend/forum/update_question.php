<?php
session_start();
include('../../Backend/db/db_connect.php');

// Ensure content type is JSON for response
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_type = $_SESSION['user_type'];

// Check if POST request is made
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $postId = isset($_POST['postId']) ? $_POST['postId'] : null;
    $title = isset($_POST['editTitle']) ? mysqli_real_escape_string($con, $_POST['editTitle']) : '';
    $description = isset($_POST['editDescription']) ? mysqli_real_escape_string($con, $_POST['editDescription']) : '';

    // Validate input
    if (empty($postId) || empty($title) || empty($description)) {
        echo json_encode(['status' => 'error', 'message' => 'Missing data. Please fill out all fields.']);
        exit();
    }

    // Check if an image was uploaded
    if (!empty($_FILES['editImage']['name'])) {
        $image = $_FILES['editImage']['name'];
        $target_dir = "../../uploads/forum_posts/";
        $target_file = $target_dir . basename($image);

        // Fetch the current image from the database
        $query = "SELECT image FROM forum WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $postId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentImage);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Delete the old image if it exists
        if (!empty($currentImage) && file_exists($target_dir . $currentImage)) {
            unlink($target_dir . $currentImage);
        }

        // Move the new image to the target directory
        if (move_uploaded_file($_FILES['editImage']['tmp_name'], $target_file)) {
            $query = "UPDATE forum SET title = ?, description = ?, image = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, 'sssi', $title, $description, $image, $postId);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
            exit();
        }
    } else {
        // Update without image
        $query = "UPDATE forum SET title = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $description, $postId);
    }

    // Execute the query and check for errors
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['status' => 'success', 'message' => 'Post updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating post: ' . mysqli_error($con)]);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);
}
?>
