<?php
session_start();
include("../db/function.php");
// die($_SESSION['id']);
if(isset($_POST['submit'])){
    include("../db/db_connect.php");

    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);
    $sql = "SELECT * FROM tbl_users WHERE username = '$username' AND password = '$password'";
    $query = $con->query($sql);
    $row = $query->fetch_assoc();

    if($query->num_rows != 0){
        $_SESSION['id'] = $row['id'];
        $_SESSION['user_type'] = $row['user_type']; // Adding user type to the session

        if($row['user_type'] == "farmer"){
            header("Location: ../../Frontend/Farmer/dashboard.php");
        } else if($row['user_type'] == "buyer") {
            header("Location: ../../Frontend/Buyer/dashboard.php");
        } else if($row['user_type'] == "admin"){
            header("Location: ../../Frontend/Admin/dashboard.php");
        }
        exit();
    } else {
        // die("Error: ".$con->error);
        $_SESSION['status'] = "Incorrect Username or Password";
        header('Location: ../../Frontend/login.php');
        exit();
    }
}
?>
