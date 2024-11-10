<?php
include('../../Backend/db/db_connect.php');

// if(isset($_POST['sender']) && isset($_POST['receiver'])) {
    $sender = $_GET['sender'];
    $receiver = $_GET['receiver'];
    $gochat_id = rand();

    $status = 'request';

    try {
        $query = "INSERT INTO chat (sender, receiver, gochat_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "iii", $sender, $receiver, $gochat_id);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $query2 = "INSERT INTO messages (gochat_id, status) VALUES (?,?)";
            $stmt2 = mysqli_prepare($con, $query2);
            mysqli_stmt_bind_param($stmt2, "is", $gochat_id, $status);
            $res2 = mysqli_stmt_execute($stmt2);
        }

        if ($res) {
            echo json_encode(['res' => 'success']);
        } else {
            echo json_encode(['res' => 'error', 'message' => 'Something went wrong']);
        }

        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
// } else {
//     echo json_encode(['error' => 'Missing reference']);
// }

?>