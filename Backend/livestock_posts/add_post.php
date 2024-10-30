<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form inputs
    $title = $_POST['title'];
    $description = $_POST['description'];
    $livestock_type = $_POST['livestock_type'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $health_status = $_POST['health_status'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $image = $_FILES['image_posts']; // Use the correct input name for the image upload

    $farmer_id = $_SESSION['id']; // Assuming you have the farmer's ID in session

    // Verify that the farmer ID exists in tbl_users
    $query = "SELECT COUNT(*) FROM tbl_users WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $farmer_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($count == 0) {
        // Invalid user ID, handle error
        $response = array(
            'status' => 'error',
            'message' => 'Invalid user ID'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Check for duplicate post (same title and description within a short timeframe)
    $checkQuery = "SELECT COUNT(*) FROM livestock_posts 
                   WHERE farmer_id = ? 
                   AND title = ? 
                   AND description = ? 
                   AND DATE(date_posted) = CURDATE()"; // Modify time condition as needed
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, 'iss', $farmer_id, $title, $description);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $duplicateCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($duplicateCount > 0) {
        // Duplicate post detected
        $response = array(
            'status' => 'error',
            'message' => 'Duplicate post detected. Please check your title and description.'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    // Handle file upload if present
    $imageName = null; // Initialize the image name
    if ($image['error'] === UPLOAD_ERR_OK) {
        $target_dir = "../../uploads/livestock_posts/";
        $imageName = basename($image['name']);
        $target_file = $target_dir . $imageName;

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $target_file)) {
            $imageName = null; // Handle file upload failure
        }
    }

    // Insert post into the database
    $insertQuery = "INSERT INTO livestock_posts (farmer_id, title, description, livestock_type, breed, age, weight, health_status, location, price, quantity, image_posts) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $insertQuery);
    mysqli_stmt_bind_param($stmt, 'issssssssdds', $farmer_id, $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $imageName);

    if ($stmt->execute()) {
        $post_id = mysqli_insert_id($con); // Get the last inserted ID
        
        // Fetch newly inserted post details
        $selectQuery = "SELECT title, description, livestock_type, breed, age, weight, health_status, location, price, quantity, image_posts, date_posted 
                        FROM livestock_posts WHERE post_id = ?";
        $stmt = mysqli_prepare($con, $selectQuery);
        mysqli_stmt_bind_param($stmt, 'i', $post_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $image_posts, $date_posted);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Prepare the response
        $response = array(
            'status' => 'success',
            'message' => 'Post added successfully.',
            'post' => array(
                'title' => $title,
                'description' => $description,
                'livestock_type' => $livestock_type,
                'breed' => $breed,
                'age' => $age,
                'weight' => $weight,
                'health_status' => $health_status,
                'location' => $location,
                'price' => $price,
                'quantity' => $quantity,
                'image_posts' => $imageName ? 'livestock_posts/' . $imageName : null,
                'date_posted' => date('F j, Y', strtotime($date_posted))
            )
        );

        // Send the JSON response to the front end (if needed)
        header('Content-Type: application/json');
        echo json_encode($response);

        // Redirect to browse_livestock.php after a successful post
        header('Location: ../../Frontend/Farmer/browse_livestock.php'); // Update the path as needed
        exit(); // Ensure no further code is executed
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Failed to add post'
        );
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // Close the statement and connection
    $stmt->close();
    $con->close();
}
?>
