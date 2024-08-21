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

    // Check for duplicate post (same title and description within a short timeframe)
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
        $target_dir = "../../Uploads";
        $target_file = $target_dir . basename($image);
        if (!move_uploaded_file($_FILES['questionImage']['tmp_name'], $target_file)) {
            $image = null; // Handle file upload failure
        }
    } else {
        $image = null;
    }

    // Insert into database
    $query = "INSERT INTO forum (user_id, title, description, image, created_at) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'issss', $user_id, $title, $description, $image, $created_at);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Fetch user data for the post
    $query = "SELECT first_name, last_name FROM tbl_users WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $first_name, $last_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Prepare response
    if ($success) {
        $response = array(
            'status' => 'success',
            'message' => 'Post added successfully.',
            'post' => array(
                'first_name' => $first_name,
                'last_name' => $last_name,
                'title' => $title,
                'description' => $description,
                'image' => $image ? 'Uploads' . $image : null,
                'created_at' => $created_at
            )
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to add post.'
        );
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
        