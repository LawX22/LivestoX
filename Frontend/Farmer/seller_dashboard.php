<?php
session_start();
include('../../Backend/db/db_connect.php'); 

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
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
    <title>LivestoX - Seller Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/seller_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'seller_dashboard';
            include('../../sidebar/sidebar-farmer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="logo">LivestoX Logo Here</div>
            </header>

            <div class="dashboard-content">
                <!-- Overview Section -->
                <div class="dashboard-section">
                    <h2>Overview</h2>
                    <div class="overview-cards">
                        <div class="card">
                            <i class="fas fa-comments"></i>
                            <div>
                                <h3>5</h3>
                                <p>Chats to answer</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-star" style="color: yellow;"></i>
                            <div>
                                <h3>3</h3>
                                <p>Seller rating</p>
                                <span>2 Reviews</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Your Listings Section -->
                <div class="dashboard-section">
                    <h2>Your Livestock Listings</h2>
                    <div class="listing-cards">
                        <div class="card">
                            <i class="fas fa-paw"></i> 
                            <div>
                                <h3>12</h3>
                                <p>All Livestock Listings</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h3>8</h3>
                                <p>Active & pending</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-shopping-cart"></i>
                            <div>
                                <h3>2</h3>
                                <p>Sold & out of stock</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-pencil-alt"></i>
                            <div>
                                <h3>1</h3>
                                <p>Drafts</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-trash-alt"></i>
                            <div>
                                <h3>1</h3>
                                <p>To delete & relist</p>
                            </div>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="create-listing">Create new listing</button> 
                    </div>
                </div>

                <!-- Marketplace Insights Section -->
                <div class="dashboard-section">
                    <h2>Marketplace insights</h2>
                    <div class="insight-cards">
                        <div class="card">
                            <i class="fas fa-eye"></i>
                            <div>
                                <h3>22</h3>
                                <p>Clicks on listings</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-bookmark"></i>
                            <div>
                                <h3>5</h3>
                                <p>Listing saves</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-user-friends"></i> 
                            <div>
                                <h3>10</h3>
                                <p>Forum Activity</p>
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
