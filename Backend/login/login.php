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
        @header("Location: ../../Frontend/farmers.php");
        }else if($row['user_type'] == "buyer") {
        @header("Location: ../../Frontend/buyers.php");
        }else if($row['user_type'] == "admin"){
        @header("Location: ../../Frontend/admin.php");
        }
        exit();
    } else {
      // die("Error: ".$con->error);
        $_SESSION['status'] = "Incorrect Username or Password";
        header('location: ../../Frontend/login.php');
        exit();
        
    }
}
?>