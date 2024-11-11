<?php
include('../../Backend/db/db_connect.php');

// if(isset($_POST['sender']) && isset($_POST['receiver'])) {
    $sender = $_GET['sender'];
    $receiver = $_GET['receiver'];
    $gochat_id = rand();

    $status = 'request';

    try {
        $checkQuery = "SELECT * FROM chat WHERE sender = ? AND receiver = ?";
        $checkStmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "ii", $sender, $receiver);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            echo json_encode(['res' => 'exists', 'message' => 'Chat request already exists.']);
        } else {
            $query = "INSERT INTO chat (sender, receiver, status, gochat_id) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "iisi", $sender, $receiver, $status, $gochat_id);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $query2 = "INSERT INTO messages (gochat_id) VALUES (?)";
                $stmt2 = mysqli_prepare($con, $query2);
                mysqli_stmt_bind_param($stmt2, "i", $gochat_id);
                $res2 = mysqli_stmt_execute($stmt2);
            }

            if ($res && $res2) {
                echo json_encode(['res' => 'success']);
            } else {
                echo json_encode(['res' => 'error', 'message' => 'Something went wrong']);
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($checkStmt);

    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
// } else {
//     echo json_encode(['error' => 'Missing reference']);
// }

?>
