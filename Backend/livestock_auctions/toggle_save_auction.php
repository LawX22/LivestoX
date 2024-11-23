<?php
session_start();
include('../db/db_connect.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'buyer') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if (!isset($_POST['auction_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing auction ID'
    ]);
    exit();
}

$user_id = $_SESSION['id'];
$auction_id = intval($_POST['auction_id']);

// Check if auction is already saved
$check_query = "SELECT id FROM saved_auctions 
                WHERE user_id = ? AND auction_id = ?";
$stmt = mysqli_prepare($con, $check_query);
mysqli_stmt_bind_param($stmt, 'ii', $user_id, $auction_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Remove from saved auctions
    $delete_query = "DELETE FROM saved_auctions 
                    WHERE user_id = ? AND auction_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $auction_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Auction removed from saved items',
            'saved' => false
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error removing auction from saved items'
        ]);
    }
} else {
    // Add to saved auctions
    $insert_query = "INSERT INTO saved_auctions (user_id, auction_id) 
                    VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $insert_query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $auction_id);
    
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Auction saved successfully',
            'saved' => true
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error saving auction'
        ]);
    }
}
