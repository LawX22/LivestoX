<?php
// session_start();
// include('../../Backend/db/db_connect.php');

// if(isset($_GET['postid'])) {
    $user_id = $_SESSION['id'];

    // try {
        $query = "SELECT lp.*, u.id, u.first_name, u.last_name 
                FROM livestock_posts lp 
                JOIN tbl_users u ON lp.farmer_id = u.id 
                WHERE u.id = ?
                ORDER BY lp.date_posted DESC
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