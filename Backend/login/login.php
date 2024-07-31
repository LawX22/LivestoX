<?php
session_start();
include("../db/function.php");
// die($_SESSION['id']);
if(isset($_POST['submit'])){
    include("../db/db_connect.php");
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM tbl_users WHERE `username` = '$username' AND `password` = '$password'";
    $query = $con->query($sql);
    $row = $query->fetch_array();

    if($query->num_rows != 0){
        $_SESSION['id'] = $row['id'];
        if($row['user_type'] == "farmer"){
        @header("Location: ../../frontend/farmers.php");
        }else if($row['user_type'] == "buyer") {
        @header("Location: ../../frontend/buyers.php");
        }else if($row['user_type'] == "admin"){
        @header("Location: ../../frontend/admin.php");
        }
        exit();
    } else {
      // die("Error: ".$con->error);
        echo('<script>alert("Wrong username or password!");window.location = "../../frontend/login.php";</script>');
        exit();
        
    }
}
?>