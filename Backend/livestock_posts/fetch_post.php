<?php
session_start();
include('../../Backend/db/db_connect.php');

$sql = "SELECT * FROM livestock_posts WHERE farmer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['id']); // Use session 'id' to match user ID
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);

$stmt->close();
$conn->close();
?>
