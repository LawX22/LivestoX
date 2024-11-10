<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include('../../Backend/db/db_connect.php');

$uid = $_GET['uid'];


while (true) {
    try {
        $query = "SELECT messages.*, chat.status
                FROM messages
                LEFT JOIN chat ON chat.gochat_id = messages.gochat_id
                WHERE messages.gochat_id = ?
                ";
        
        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $messages = [];
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }

            echo "data: " . json_encode($messages) . "\n\n";
            
            $stmt->close();
        } else {
            echo "data: " . json_encode(['error' => 'Failed to prepare statement']) . "\n\n";
        }
    } catch (Exception $e) {
        echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
    }
    ob_flush();
    flush();
    sleep(1);
}
?>
