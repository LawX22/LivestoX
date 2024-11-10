<?php
    include('../../Backend/db/db_connect.php');

    $rawData = file_get_contents('php://input');
    $data = json_decode($rawData, true);

    $user_id = $data['user_id'] ?? null;
    $content = $data['content'] ?? null;
    $image = $data['image'] ?? null;

    $gochat_id = $_GET['uuid'];

    try {
        $query = "INSERT INTO messages (user_id, gochat_id, content, image_url) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "iiss", $user_id, $gochat_id, $content, $image);
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

?>