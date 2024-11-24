<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $bio = $_POST['bio'] ?? '';

    if (empty($bio)) {
        echo json_encode(['success' => false, 'message' => 'Bio cannot be empty.']);
        exit;
    }

    // Update the user's bio in the database
    $sql = "UPDATE tbl_users SET bio = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $bio, $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
                'success' => true,
                'bio' => htmlspecialchars($bio),
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed.']);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
