<?php
session_start();
include('../../Backend/db/db_connect.php');

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
    $image_posts = $_POST['image_posts']; 

    // Check if post_id is provided
    if (empty($post_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Post ID is required']);
        exit();
    }

    $sql = "UPDATE livestock_posts SET title=?, description=?, livestock_type=?, breed=?, age=?, weight=?, health_status=?, location=?, price=?, quantity=?, image_posts=? 
            WHERE post_id=? AND farmer_id=?"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssdssi", $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $image_posts, $post_id, $_SESSION['id']); // Check farmer_id

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Post updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update post']);
    }

    $stmt->close();
    $conn->close();
}
?>
