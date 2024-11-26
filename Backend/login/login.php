<?php
session_start();
include("../db/db_connect.php");
header('Content-Type: application/json'); // Set content type to JSON

// Check if the form is submitted via POST
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind the query to check for the user by username
    $stmt = $con->prepare("SELECT * FROM tbl_users WHERE username = ?");
    $stmt->bind_param("s", $username); // "s" means string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 0) {
        // User doesn't exist
        echo json_encode([
            'status' => 'error',
            'message' => 'Account does not exist. Please sign up first.'
        ]);
        exit();
    } else {
        // User exists, now check if password matches
        $row = $result->fetch_assoc();

        // Direct password comparison (no hashing, as per your request)
        if ($password === $row['password']) {
            // Password matches, start the session
            $_SESSION['id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];

            // Determine where to redirect based on user type
            $redirect_url = '';
            if ($row['user_type'] == 'farmer') {
                $redirect_url = '../../Livestox/Frontend/Farmer/browse_livestock.php';
            } elseif ($row['user_type'] == 'buyer') {
                $redirect_url = '../../Livestox/Frontend/Buyer/browse_livestock.php';
            } elseif ($row['user_type'] == 'admin') {
                $redirect_url = '../../Livestox/Frontend/Admin/admin_dashboard.php';
            }

            // Return success response with the redirect URL
            echo json_encode([
                'status' => 'success',
                'redirect_url' => $redirect_url
            ]);
            exit();
        } else {
            // Incorrect password
            echo json_encode([
                'status' => 'error',
                'message' => 'Incorrect password. Please try again.'
            ]);
            exit();
        }
    }
}

?>
