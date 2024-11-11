<?php
include('../../Backend/db/db_connect.php');

// if(isset($_POST['sender']) && isset($_POST['receiver'])) {
    $gochat_id = $_GET['uid'];
    $status = 'accepted';

    try {
        $query = "UPDATE chat SET status = ? WHERE gochat_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $gochat_id);
        $res = mysqli_stmt_execute($stmt);

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