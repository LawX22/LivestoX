<?php
session_start();
session_unset();
session_destroy();

$response = [
    "status" => "success",
    "redirect_url" => "../../Frontend/landingpage.php",
];

echo json_encode($response);
exit();
?>

