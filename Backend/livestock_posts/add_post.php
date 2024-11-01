<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $post_id = $_POST['post_id']; // Assuming you pass the post ID in the request
    $title = $_POST['title'];
    $description = $_POST['description'];
    $livestock_type = $_POST['livestock_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $health_status = $_POST['health_status'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image_posts']; // Use the correct input name for the image upload

    $farmer_id = $_SESSION['id']; // Ensure this is set correctly

    // Verify that the post exists and belongs to the farmer
    $query = "SELECT farmer_id, image_posts FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $existing_farmer_id, $existing_image);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($existing_farmer_id !== $farmer_id) {
        // Farmer ID does not match, handle error
        $response = array('status' => 'error', 'message' => 'Unauthorized action.');
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Handle file upload if present
    $imageName = $existing_image; // Default to the existing image
    if ($image['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/livestock_posts/";
        $imageName = basename($image['name']);
        $target_file = $target_dir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            $response = array('status' => 'error', 'message' => 'Failed to upload image.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    // Update the post in the database
    $updateQuery = "UPDATE livestock_posts 
                    SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, 
                        health_status = ?, location = ?, price = ?, quantity = ?, image_posts = ?
                    WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ssssssssssssi', $title, $description, $livestock_type, $breed, $age, $weight, 
                                         $health_status, $location, $price, $quantity, $imageName, $post_id);

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Post updated successfully.');
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update post.');
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>
