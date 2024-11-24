<?php
session_start();
include('../../Backend/db/db_connect.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];
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
    $image = $_FILES['image_posts'];

    $farmer_id = $_SESSION['id'];

    // Verify the post
    $query = "SELECT farmer_id, image_posts FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $owner_id, $old_image);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if (!$owner_id || $owner_id != $farmer_id) {
        http_response_code(403); // Forbidden
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized update']);
        exit();
    }

    $imageName = null;
    if ($image['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/livestock_posts/";
        $imageName = basename($image['name']);
        $target_file = $target_dir . $imageName;

        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            if ($old_image) {
                $old_image_path = $target_dir . $old_image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
        } else {
            $imageName = null; // Reset imageName if upload fails
        }
    }

    if ($imageName === null) {
        $stmt = mysqli_prepare($con, "UPDATE livestock_posts SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, health_status = ?, location = ?, price = ?, quantity = ? WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt, 'ssssssssssd', $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $post_id);
    } else {
        $updateQuery = "UPDATE livestock_posts SET title = ?, description = ?, livestock_type = ?, breed = ?, age = ?, weight = ?, health_status = ?, location = ?, price = ?, quantity = ?, image_posts = ? WHERE post_id = ?";
        $stmt = mysqli_prepare($con, $updateQuery);
        mysqli_stmt_bind_param($stmt, 'sssssssssssd', $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $imageName, $post_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Livestock updated successfully']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['status' => 'error', 'message' => 'Failed to update livestock']);
    }

    $stmt->close();
    $con->close();
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
