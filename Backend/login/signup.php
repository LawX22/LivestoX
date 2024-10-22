<?php
session_start();

include("../db/db_connect.php");
include("../db/function.php");

if (isset($_POST['submit'])) {
    $role = $_POST['role'];
    $fname = sec_input($_POST['fname']);
    $lname = sec_input($_POST['lname']);
    $username = sec_input($_POST['username']);
    $phone = sec_input($_POST['phone']);
    $email = sec_input($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Check if username or email already exists
    $sql = "SELECT * FROM tbl_users WHERE username='$username' OR email='$email' LIMIT 1";
    $query = $con->query($sql);
    $row = $query->fetch_array();

    if ($row) {
        if ($row['username'] === $username) {
            echo ('<script>alert("Username already taken");window.location = "../../Frontend/signup.php";</script>');
            exit();
        }
        if ($row['email'] === $email) {
            echo ('<script>alert("Email is already taken");window.location = "../../Frontend/signup.php";</script>');
            exit();
        }
    }

    // Check if password fields match and required fields are not empty
    if (!empty($username) && !empty($password) && !empty($email)) {
        if ($password === $password2) {

            // Generate a unique user_id based on role and a random number
            $randomNumber = time() .rand(100000, 999999);  // Generate a random number
            $user_id = strtolower($role) . "_" . $randomNumber;  // e.g. "farmer_796696" or "buyer_68587"
            
            // Insert the user into the database with the unique user_id
            $query = "INSERT INTO tbl_users (user_id, first_name, last_name, username, phone, email, password, user_type) 
                      VALUES ('$user_id', '$fname', '$lname', '$username', '$phone', '$email', '$password', '$role')";

            mysqli_query($con, $query);
            header("Location: ../../Frontend/login.php");
            die;
        } else {
            echo ('<script>alert("Passwords do not match");window.location = "../../Frontend/signup.php";</script>');
        }
    } else {
        echo ('<script>alert("Please fill all required fields");window.location = "../../Frontend/signup.php";</script>');
    }
}

function sec_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
