<?php
session_start();
include("../db/function.php");

if (isset($_POST['submit'])) {
    include("../db/db_connect.php");

    $username = $con->real_escape_string($_POST['username']);
    $password = $con->real_escape_string($_POST['password']);
    $sql = "SELECT * FROM tbl_users WHERE username = '$username'";
    $query = $con->query($sql);

    // Check if any user exists with the entered username
    if ($query->num_rows === 0) {
        $_SESSION['status'] = "Account does not exist. Please sign up first.";
        header('Location: ../../Frontend/login.php');
        exit();
    } else {
        $sql = "SELECT * FROM tbl_users WHERE username = '$username' AND password = '$password'";
        $query = $con->query($sql);
        $row = $query->fetch_assoc();

        // Check if username and password match
        if ($query->num_rows != 0) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['user_type'] = $row['user_type'];

            if ($row['user_type'] == "farmer") {
                header("Location: ../../Frontend/Farmer/browse_livestock.php");
            } else if ($row['user_type'] == "buyer") {
                header("Location: ../../Frontend/Buyer/browse_livestock.php");
            } else if ($row['user_type'] == "admin") {
                header("Location: ../../Frontend/Admin/browse_livestock.php");
            }
            exit();
        } else {
            $_SESSION['status'] = "Incorrect Password";
            header('Location: ../../Frontend/login.php');
            exit();
        }
    }
}
?>
