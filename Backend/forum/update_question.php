<?php
session_start();
include('../../Backend/db/db_connect.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Get the user type from the session
$user_type = $_SESSION['user_type'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postId = $_POST['postId'];
    $title = mysqli_real_escape_string($con, $_POST['editTitle']);
    $description = mysqli_real_escape_string($con, $_POST['editDescription']);
    
    // Handle image upload if a new image is provided
    if (!empty($_FILES['editImage']['name'])) {
        $image = $_FILES['editImage']['name'];
        $target_dir = "../../uploads/forum/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['editImage']['tmp_name'], $target_file);
        
        $query = "UPDATE forum SET title = ?, description = ?, image = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', $title, $description, $image, $postId);
    } else {
        // Update without image
        $query = "UPDATE forum SET title = ?, description = ? WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, 'ssi', $title, $description, $postId);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect based on user type
        if ($user_type === 'farmer') {
            header("Location: ../../Frontend/Farmer/livestock_forum.php");
        } elseif ($user_type === 'buyer') {
            header("Location: ../../Frontend/Buyer/livestock_forum.php");
        }
        exit();
    } else {
        echo "Error updating post: " . mysqli_error($con);
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
