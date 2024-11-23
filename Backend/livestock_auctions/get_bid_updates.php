<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

include('../../Backend/db/db_connect.php');

// Prevent buffer issues
ob_end_clean();
set_time_limit(0);
ini_set('display_errors', 'off');

function sendSSEMessage($data) {
    echo "data: " . json_encode($data) . "\n\n";
    ob_flush();
    flush();
}

// Get auction ID from query parameter
$auction_id = isset($_GET['auction_id']) ? intval($_GET['auction_id']) : 0;

if ($auction_id <= 0) {
    sendSSEMessage(['error' => 'Invalid auction ID']);
    exit();
}

$lastEventId = isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? 
    intval($_SERVER["HTTP_LAST_EVENT_ID"]) : 0;

while (true) {
    // Check for new bids
    $query = "
        SELECT 
            bh.id,
            bh.bidder_id,
            bh.amount,
            bh.bid_time,
            CONCAT(u.first_name, ' ', u.last_name) as bidder_name
        FROM bid_history bh
        JOIN tbl_users u ON bh.bidder_id = u.id
        WHERE bh.auction_id = ? AND bh.id > ?
        ORDER BY bh.bid_time ASC
    ";
    
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $auction_id, $lastEventId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $lastEventId = $row['id'];
        sendSSEMessage([
            'id' => $row['id'],
            'bidder_name' => htmlspecialchars($row['bidder_name']),
            'amount' => number_format($row['amount'], 2),
            'bid_time' => date('Y-m-d H:i:s', strtotime($row['bid_time']))
        ]);
    }
    
    mysqli_stmt_close($stmt);
    
    // Prevent CPU overload
    sleep(1);
    
    // Clear connection status
    if (connection_status() != CONNECTION_NORMAL) {
        break;
    }
}