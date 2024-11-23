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

if (!isset($_POST['auction_id']) || !isset($_POST['bid_amount'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required parameters'
    ]);
    exit();
}

$auction_id = intval($_POST['auction_id']);
$bid_amount = floatval($_POST['bid_amount']);
$bidder_id = $_SESSION['id'];

// Get auction details
$query = "SELECT * FROM livestock_auctions WHERE id = ? AND status = 'active' AND end_time > NOW()";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $auction_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$auction = mysqli_fetch_assoc($result);

if (!$auction) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid auction or auction has ended'
    ]);
    exit();
}

// Check if bid is higher than current highest bid
$current_highest = $auction['current_highest_bid'] ?: $auction['starting_price'];
if ($bid_amount <= $current_highest) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Bid must be higher than current highest bid'
    ]);
    exit();
}

// Start transaction
mysqli_begin_transaction($con);

try {
    // Update auction with new highest bid
    $update_query = "UPDATE livestock_auctions SET 
                    current_highest_bid = ?,
                    highest_bidder_id = ?
                    WHERE id = ?";
    $stmt = mysqli_prepare($con, $update_query);
    mysqli_stmt_bind_param($stmt, 'dii', $bid_amount, $bidder_id, $auction_id);
    mysqli_stmt_execute($stmt);

    // Record the bid in bid history
    $history_query = "INSERT INTO bid_history 
                     (auction_id, bidder_id, amount, bid_time) 
                     VALUES (?, ?, ?, NOW())";
    $stmt = mysqli_prepare($con, $history_query);
    mysqli_stmt_bind_param($stmt, 'iid', $auction_id, $bidder_id, $bid_amount);
    mysqli_stmt_execute($stmt);

    mysqli_commit($con);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Bid placed successfully!'
    ]);
} catch (Exception $e) {
    mysqli_rollback($con);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error placing bid'
    ]);
}