<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['questionTitle'];
    $description = $_POST['questionDescription'];
    $image = $_FILES['questionImage']['name'] ?? null;

    $user_id = $_SESSION['id'];
  
    date_default_timezone_set("Asia/Manila");
    $created_at = date('Y-m-d H:i:s');
    
    // Verify that the user_id exists in tbl_users
    $query = "SELECT COUNT(*) FROM tbl_users WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count == 0) {
        $response = array(
            'status' => 'error',
            'message' => 'Invalid user ID'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Check for duplicate post
    $checkQuery = "SELECT COUNT(*) FROM forum 
                   WHERE user_id = ? 
                   AND title = ? 
                   AND description = ? 
                   AND created_at > NOW() - INTERVAL 1 MINUTE";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'iss', $user_id, $title, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $duplicateCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($duplicateCount > 0) {
        $response = array(
            'status' => 'error',
            'message' => 'Duplicate post detected. Please wait before posting again.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Handle file upload if present
    if ($image) {
        $target_dir = "../../uploads/forum_posts/";
        $target_file = $target_dir . basename($image);
        if (!move_uploaded_file($_FILES['questionImage']['tmp_name'], $target_file)) {
            $image = null; // Handle file upload failure
        }
    }

    // Insert post into the database
    $insertQuery = "INSERT INTO forum (user_id, title, description, image, created_at)
                    VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'issss', $user_id, $title, $description, $image, $created_at);
    mysqli_stmt_execute($stmt);
    $post_id = mysqli_insert_id($con);
    mysqli_stmt_close($stmt);

    // Fetch newly inserted post details
    $selectQuery = "SELECT u.first_name, u.last_name, u.user_type, f.title, f.description, f.image, f.created_at
                    FROM forum f
                    JOIN tbl_users u ON f.user_id = u.id
                    WHERE f.id = ?";
    $stmt = mysqli_prepare($con, $selectQuery);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $first_name, $last_name, $user_type, $title, $description, $image, $created_at);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Prepare the response
    $response = array(
        'status' => 'success',
        'message' => 'Post added successfully.',
        'post' => array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_type' => $user_type,
            'title' => $title,
            'description' => $description,
            'image' => $image ? 'uploads/forum_posts/' . $image : null, // Ensure correct path
            'created_at' => $created_at,
            'profile_image' => 'uploads/profile_pictures/default.png', // Default profile image
        )
    );

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
