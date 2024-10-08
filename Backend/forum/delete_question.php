<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    $post_id = $data['post_id'];

    if (!$post_id) {
        $response = array(
            'status' => 'error',
            'message' => 'Post ID is missing.'
        );
        echo json_encode($response);
        exit();
    }

    // Ensure the post belongs to the current user (for security)
    $user_id = $_SESSION['id'];
    $query = "DELETE FROM forum WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $post_id, $user_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($success) {
        $response = array(
            'status' => 'success',
            'message' => 'Post deleted successfully.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete post. Please try again.'
        );
    }

    echo json_encode($response);
}
?>
