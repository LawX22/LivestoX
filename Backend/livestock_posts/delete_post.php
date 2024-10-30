<?php
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];

    $query = "DELETE FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Listing deleted successfully.";
    } else {
        echo "Error deleting listing.";
    }

    mysqli_stmt_close($stmt);
}
?>
