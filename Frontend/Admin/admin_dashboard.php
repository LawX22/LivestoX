<?php
session_start();
include('../../Backend/db/db_connect.php'); 

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../../Frontend/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name, profile_picture, user_type FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name, $profile_picture, $user_type);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Default profile picture
$default_profile_picture = '../../Assets/default-profile.png';

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
    <title>LivestoX - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'admin_dashboard';
            include('../../sidebar/sidebar-admin.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- User Management Section -->
                <div class="dashboard-section">
                    <h2>User Management</h2>
                    <div class="overview-cards">
                        <div class="card">
                            <i class="fas fa-users"></i>
                            <div>
                                <h3>120</h3>
                                <p>Total Users</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-user-check"></i>
                            <div>
                                <h3>30</h3>
                                <p>Active Users</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-user-slash"></i>
                            <div>
                                <h3>5</h3>
                                <p>Banned Users</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Livestock Listings Section -->
                <div class="dashboard-section">
                    <h2>Livestock Listings</h2>
                    <div class="listing-cards">
                        <div class="card">
                            <i class="fas fa-paw"></i> 
                            <div>
                                <h3>50</h3>
                                <p>Total Listings</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-check"></i>
                            <div>
                                <h3>30</h3>
                                <p>Active Listings and Auctions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reports Section -->
                <div class="dashboard-section">
                    <h2>Reports</h2>
                    <div class="report-cards">
                        <div class="card">
                            <i class="fas fa-file-alt"></i>
                            <div>
                                <h3>8</h3>
                                <p>Open Reports</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-exclamation-circle"></i>
                            <div>
                                <h3>2</h3>
                                <p>Urgent Issues</p>
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
