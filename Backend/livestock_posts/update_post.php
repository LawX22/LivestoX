<?php
session_start();
include('../../Backend/db/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('HTTP/1.1 401 Unauthorized'); // Send an unauthorized header
    exit(); // Terminate the script
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form inputs
    $post_id = $_POST['post_id']; // The ID of the post to update
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
    $image = $_FILES['image_posts']; // For the image upload

    $farmer_id = $_SESSION['id']; // Get the logged-in user's ID

    // Verify that the post exists and belongs to the logged-in user
    $query = "SELECT farmer_id, image_posts FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $owner_id, $old_image);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$owner_id || $owner_id != $farmer_id) {
        // User does not have permission to update this post
        header('HTTP/1.1 401 Unauthorized'); // Send an unauthorized header
        exit(); // Terminate the script
    }

    // Handle file upload if present
    $imageName = null; // Initialize the image name
    if ($image['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/livestock_posts/";
        $imageName = basename($image['name']);
        $target_file = $target_dir . $imageName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            // If an image is successfully uploaded, delete the old image
            if ($old_image) {
                $old_image_path = $target_dir . $old_image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path); // Delete the old image file
                }
            }
        } else {
            // Handle file upload failure
            $imageName = null; // Reset imageName to null if upload fails
        }
    }

    // Update the post in the database
    if ($imageName === null) {
        // If no new image was uploaded, keep the old image
        $stmt = mysqli_prepare($con, "UPDATE livestock_posts SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, health_status = ?, location = ?, price = ?, quantity = ? WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt, 'ssssssssssd', $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $post_id);
    } else {
        // If a new image was uploaded, update with the new image name
        $updateQuery = "UPDATE livestock_posts SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, health_status = ?, location = ?, price = ?, quantity = ?, image_posts = ? WHERE post_id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'sssssssssssd', $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $imageName, $post_id);
    }

    // Execute the update statement
    if ($stmt->execute()) {
        // Redirect after successful update
        header('Location: ../../Frontend/Farmer/browse_livestock.php'); 
        exit(); // Ensure no further code is executed
    } else {
        // Log the error if the update fails
        echo "Error executing query: " . mysqli_error($con);
        exit(); // Terminate the script on error
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    header('HTTP/1.1 405 Method Not Allowed'); // Send an error header for wrong method
    exit(); // Terminate the script
}
?>
