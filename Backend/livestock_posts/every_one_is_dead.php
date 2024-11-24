<?php
// session_start();
// include('../../Backend/db/db_connect.php');

// if(isset($_GET['postid'])) {
    $user_id = $_SESSION['id'];

    // try {
        $query = "SELECT f.id, f.title, f.description, f.image, f.created_at, f.user_id, u.profile_picture, u.first_name, u.last_name, u.user_type 
                FROM forum f 
                JOIN tbl_users u ON f.user_id = u.id
                WHERE u.id = ?
                ORDER BY f.created_at DESC
                ";

        // $stmt = $con->prepare($query);
        // $stmt->bindParam(':postid', $postid);
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $no_lives_matter = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $no_lives_matter[] = $row;
        }

        // header('Content-type: application/json');
        // echo json_encode($no_lives_matter);

        mysqli_stmt_close($stmt);
    // } catch (Exception $e) {
    //     echo json_encode(['error' => $th->getMessage()]);
    // }
// } else {
//     echo json_encode(['error' => 'Missing reference']);
// }

?>
