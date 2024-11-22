<?php
session_start();
include('../../Backend/db/db_connect.php');

header('Content-Type: application/json');

// Check if user is logged in as a farmer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized action.']);
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $farmer_id = $_SESSION['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $livestock_type = $_POST['livestock_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $health_status = $_POST['health_status'];
    $location = $_POST['location'];
    $starting_price = $_POST['starting_price'];
    $current_highest_bid = $starting_price; // Initial bid is the starting price
    $quantity = $_POST['quantity'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = 'active'; // Default status

    // Handle file upload
    $image = $_FILES['image_posts'];
    if ($image['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/livestock_auctions/";
        $imageName = uniqid() . '_' . basename($image['name']);
        $target_file = $target_dir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No image uploaded.']);
        exit();
    }

    // Prepare the SQL statement for inserting the auction
    $insertQuery = "INSERT INTO livestock_auctions 
                    (farmer_id, title, description, livestock_type, breed, age, weight, 
                    health_status, location, starting_price, current_highest_bid, 
                    status, quantity, start_time, end_time, image_posts, date_posted) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'issssssssidsssss', 
        $farmer_id, $title, $description, $livestock_type, $breed, $age, $weight, 
        $health_status, $location, $starting_price, $current_highest_bid, 
        $status, $quantity, $start_time, $end_time, $imageName
    );

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Auction created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create auction: ' . $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
    exit();
}
?>