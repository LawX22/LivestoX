<?php
session_start();
include('../../Backend/db/db_connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to delete a post.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
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

    // Get the user ID from session
    $user_id = $_SESSION['id'];

    // Fetch the image filename from the post before deletion
    $fetchImageQuery = "SELECT image FROM forum WHERE id = ? AND user_id = ?";
    $fetchStmt = mysqli_prepare($con, $fetchImageQuery);
    mysqli_stmt_bind_param($fetchStmt, 'ii', $post_id, $user_id);
    mysqli_stmt_execute($fetchStmt);
    mysqli_stmt_bind_result($fetchStmt, $imageFilename);
    mysqli_stmt_fetch($fetchStmt);
    mysqli_stmt_close($fetchStmt);

    // Delete the post from the database
    $deleteQuery = "DELETE FROM forum WHERE id = ? AND user_id = ?";
    $deleteStmt = mysqli_prepare($con, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, 'ii', $post_id, $user_id);
    $success = mysqli_stmt_execute($deleteStmt);
    mysqli_stmt_close($deleteStmt);

    if ($success) {
        // Delete the image file from the server if it exists
        if (!empty($imageFilename)) {
            $imagePath = '../../uploads/forum_posts/' . $imageFilename;
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) {
                    $response = array(
                        'status' => 'success',
                        'message' => 'Post and associated image deleted successfully.'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'message' => 'Failed to delete image file.'
                    );
                }
            } else {
                $response = array(
                    'status' => 'success',
                    'message' => 'Post deleted successfully, but no image file found.'
                );
            }
        } else {
            $response = array(
                'status' => 'success',
                'message' => 'Post deleted successfully, no image associated.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to delete post. Please try again.'
        );
    }

    echo json_encode($response);
}
?>
