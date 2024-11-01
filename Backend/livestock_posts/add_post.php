<?php
session_start();
include('../../Backend/db/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    $response = array('status' => 'error', 'message' => 'Unauthorized action.');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $farmer_id = $_SESSION['id']; // Get the logged-in user's ID

    // Check if it's an update or add operation
    if (isset($_POST['post_id'])) {
        // Update operation
        $post_id = $_POST['post_id']; // Retrieve the post ID for the update
        $query = "SELECT farmer_id, image_posts FROM livestock_posts WHERE post_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'i', $post_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $existing_farmer_id, $existing_image);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Check if the post belongs to the logged-in farmer
        if ($existing_farmer_id !== $farmer_id) {
            $response = array('status' => 'error', 'message' => 'Unauthorized action.');
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
        
        // Prepare to update the post
        $imageName = $existing_image; // Keep the old image by default
    } else {
        // Add operation
        $post_id = null; // No post ID for adding
    }

    // Retrieve other form inputs
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

    // Handle file upload if present
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

        // If updating, delete the old image
        if (isset($existing_image) && $existing_image !== '') {
            $old_image_path = $target_dir . $existing_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path); // Delete the old image file
            }
        }
    } else {
        if (!isset($imageName)) {
            $imageName = isset($existing_image) ? $existing_image : null; // Keep the existing image if updating
        }
    }

    // Prepare the SQL statement for updating or inserting
    if ($post_id) {
        // Update existing post
        $updateQuery = "UPDATE livestock_posts 
                        SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, 
                            health_status = ?, location = ?, price = ?, quantity = ?, image_posts = ?
                        WHERE post_id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'ssssssssssssi', $title, $description, $livestock_type, $breed, $age, $weight, 
                                             $health_status, $location, $price, $quantity, $imageName, $post_id);
    } else {
        // Insert new post
        $insertQuery = "INSERT INTO livestock_posts (farmer_id, title, description, livestock_type, breed, age, weight, 
                                                        health_status, location, price, quantity, image_posts) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, 'isssssssssss', $farmer_id, $title, $description, $livestock_type, $breed, $age, 
                                                 $weight, $health_status, $location, $price, $quantity, $imageName);
    }

    // Execute the statement
    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => $post_id ? 'Post updated successfully.' : 'Post added successfully.');
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update or add post.');
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    // Handle incorrect request methods
    $response = array('status' => 'error', 'message' => 'Method not allowed.');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
