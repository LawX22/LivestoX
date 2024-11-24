<?php
include('../../Backend/db/db_connect.php');

// Prepare the response array
$response = array('status' => 'error', 'message' => 'An unexpected error occurred.');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    // Step 1: Retrieve the image filename
    $getImageQuery = "SELECT image_posts FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $getImageQuery);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $imageFilename);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Step 2: Check if the image file exists and delete it
    $imagePath = '../../uploads/livestock_posts/' . $imageFilename;
    if (file_exists($imagePath) && !empty($imageFilename)) {
        unlink($imagePath);  // Deletes the file
    }

    // Step 3: Delete the listing from the database
    $deleteQuery = "DELETE FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);

    if (mysqli_stmt_execute($stmt)) {
        $response['status'] = 'success';
        $response['message'] = 'Listing and associated photo deleted successfully.';
    } else {
        $response['message'] = 'Error deleting listing.';
    }

    mysqli_stmt_close($stmt);
}

header('Content-Type: application/json');
echo json_encode($response);  // Return the response as JSON
?>
