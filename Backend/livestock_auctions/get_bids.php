<?php
session_start();
include('../db/db_connect.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access'
    ]);
    exit();
}

if (!isset($_GET['auction_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing auction ID'
    ]);
    exit();
}

$auction_id = intval($_GET['auction_id']);

$query = "SELECT 
            bh.amount,
            bh.bid_time,
            CONCAT(tu.first_name, ' ', tu.last_name) as bidder_name
          FROM 
            bid_history bh
          JOIN 
            tbl_users tu ON bh.bidder_id = tu.id
          WHERE 
            bh.auction_id = ?
          ORDER BY 
            bh.amount DESC";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $auction_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$bids = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['bid_time'] = date('F d, Y h:i A', strtotime($row['bid_time']));
    $bids[] = $row;
}

echo json_encode([
    'status' => 'success',
    'bids' => $bids
]);