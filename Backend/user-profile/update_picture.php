<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updateType = $_POST['update_type'] ?? '';
    $userId = $_SESSION['id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

    if ($updateType === 'profile' && isset($_FILES['picture'])) {
        $targetDir = "../../uploads/profile_pictures/";
        $fileName = time() . "_" . basename($_FILES['picture']['name']); // Adding timestamp for unique file name
        $targetFilePath = $targetDir . $fileName;

        // Check if the file is an image
        $check = getimagesize($_FILES['picture']['tmp_name']);
        if ($check === false) {
            echo json_encode(['success' => false, 'message' => 'File is not an image.']);
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath)) {
            // Update the user's profile picture in the database
            $sql = "UPDATE tbl_users SET profile_picture = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $fileName, $userId);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'Profile picture updated successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to prepare the SQL statement.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error uploading the file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
