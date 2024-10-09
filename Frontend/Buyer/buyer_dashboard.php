<?php
session_start();
include('../../Backend/db/db_connect.php'); 

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'buyer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch user data including user_type
$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name, profile_picture, user_type FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name, $profile_picture, $user_type);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Set default profile picture
$default_profile_picture = '../../Assets/default-profile.png';

// Check if profile picture exists and file exists on server
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Buyer Dashboard</title>
    <link rel="stylesheet" href="../../css/buyer_dashboard.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'buyer_dashboard';
            include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Overview Section -->
                <div class="dashboard-section">
                    <h2>Overview</h2>
                    <div class="overview-cards">
                        <div class="card">
                            <i class="fas fa-bell"></i>
                            <div>
                                <h3>4</h3>
                                <p>Notification</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-shopping-cart"></i>
                            <div>
                                <h3>5</h3>
                                <p>Purchase History</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-heart"></i>
                            <div>
                                <h3>8</h3>
                                <p>Saved Listings</p>
                            </div>
                        </div>
                    </div>
                </div>
             
            </div>
        </div>
    </div>
</body>

<script src="../../js/logout-confirmation.js"></script>
</html>
