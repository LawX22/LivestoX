<?php
session_start();
include('../../Backend/db/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Prepare the query to fetch the livestock post data
    $query = "SELECT title, description, livestock_type, breed, age, weight, health_status, location, price, quantity, image_posts FROM livestock_posts WHERE post_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $title, $description, $livestock_type, $breed, $age, $weight, $health_status, $location, $price, $quantity, $image_posts);
    
    if (mysqli_stmt_fetch($stmt)) {
        // Return the data as a JSON response
        $response = array(
            'status' => 'success',
            'data' => array(
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
                'image_posts' => $image_posts // You might want to handle the image display separately
            )
        );
    } else {
        $response = array('status' => 'error', 'message' => 'Post not found.');
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    
    // Set content type to JSON and return the response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Invalid request.');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
