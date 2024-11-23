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

$user_id = $_SESSION['id'];

$query = "SELECT auction_id FROM saved_auctions WHERE user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$saved_auctions = [];
while ($row = mysqli_fetch_assoc($result)) {
    $saved_auctions[] = $row['auction_id'];
}

echo json_encode([
    'status' => 'success',
    'saved_auctions' => $saved_auctions
]);