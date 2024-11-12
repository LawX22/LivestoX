<?php
session_start();
include('../../Backend/db/db_connect.php');

// if(isset($_GET['postid'])) {
    $user_id = $_SESSION['id'];

    try {
        $query = "SELECT 
                    chat.chat_id,
                    chat.sender,
                    chat.receiver,
                    chat.gochat_id,
                    chat.status,
                    chat.created,
                    CASE
                        WHEN chat.sender = ? THEN CONCAT(receiver_users.first_name, ' ', receiver_users.last_name)
                        WHEN chat.receiver = ? THEN CONCAT(sender_users.first_name, ' ', sender_users.last_name)
                    END AS full_name,
                    CASE
                        WHEN chat.sender = ? THEN receiver_users.profile_picture
                        WHEN chat.receiver = ? THEN sender_users.profile_picture
                    END AS profile_picture
                FROM chat
                LEFT JOIN tbl_users AS sender_users ON chat.sender = sender_users.id 
                LEFT JOIN tbl_users AS receiver_users ON chat.receiver = receiver_users.id 
                WHERE chat.sender = ? OR chat.receiver = ?
                ORDER BY chat.created DESC;
                ";

        // $stmt = $con->prepare($query);
        // $stmt->bindParam(':postid', $postid);
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "iiiiii", $user_id, $user_id, $user_id, $user_id, $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $chats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $chats[] = $row;
        }

        header('Content-type: application/json');
        echo json_encode($chats);

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode(['error' => $th->getMessage()]);
    }
// } else {
//     echo json_encode(['error' => 'Missing reference']);
// }

?>