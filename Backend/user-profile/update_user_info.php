<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    $location = $_POST['location'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if (empty($location) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Update the user's information in the database
    $sql = "UPDATE tbl_users SET location = ?, phone = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $location, $phone, $userId);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode([
                'success' => true,
                'location' => htmlspecialchars($location),
                'phone' => htmlspecialchars($phone),
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
