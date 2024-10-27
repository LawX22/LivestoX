<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];

    // Check if post_id is provided
    if (empty($post_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Post ID is required']);
        exit();
    }

    $sql = "DELETE FROM livestock_posts WHERE post_id = ? AND farmer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $_SESSION['id']); // Use session 'id' to match user ID

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Post deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete post']);
    }

    $stmt->close();
    $conn->close();
}
?>
