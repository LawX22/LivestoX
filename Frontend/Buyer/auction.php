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
    <title>LivestoX - Auction Page</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/auction.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'auction';
            include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
                <div class="search">
                    <input type="text" placeholder="Search Livestock">
                </div>
            </header>

            <!-- Auction Cards List -->
            <div class="auction-list">
                <!-- Auction Card 1 -->
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="../../Assets/Cow-Gang.jpg" alt="Livestock Image">
                    </div>
                    <div class="auction-details">
                        <h2 class="auction-title">Livestock Auction Title</h2>
                        <p class="auction-description">Brief description of the livestock being auctioned.</p>
                        <div class="bidding-info">
                            <p>Opening Bid: <span class="opening-bid">$500.00</span></p>
                            <p>Current Bid: <span class="current-bid">$850.00</span></p>
                        </div>
                        <div class="time-remaining">
                            <p>Time Remaining: 2d 5h 32m</p>
                        </div>
                        <a href="#" class="view-details-btn">View Full Details</a>
                        <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                    </div>
                </div>

                <!-- Auction Card 2 -->
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="../../Assets/Goat-Gang.jpg" alt="Livestock Image">
                    </div>
                    <div class="auction-details">
                        <h2 class="auction-title">Another Auction Title</h2>
                        <p class="auction-description">This is a brief description for another auction item.</p>
                        <div class="bidding-info">
                            <p>Opening Bid: <span class="opening-bid">$750.00</span></p>
                            <p>Current Bid: <span class="current-bid">$1,000.00</span></p>
                        </div>
                        <div class="time-remaining">
                            <p>Time Remaining: 1d 3h 12m</p>
                        </div>
                        <a href="#" class="view-details-btn">View Full Details</a>
                        <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                    </div>
                </div>

                <!-- Auction Card 3 -->
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="../../Assets/Livestock.jpg" alt="Livestock Image">
                    </div>
                    <div class="auction-details">
                        <h2 class="auction-title">Another Auction Title</h2>
                        <p class="auction-description">This is a brief description for another auction item.</p>
                        <div class="bidding-info">
                            <p>Opening Bid: <span class="opening-bid">$750.00</span></p>
                            <p>Current Bid: <span class="current-bid">$1,000.00</span></p>
                        </div>
                        <div class="time-remaining">
                            <p>Time Remaining: 1d 3h 12m</p>
                        </div>
                        <a href="#" class="view-details-btn">View Full Details</a>
                        <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                    </div>
                </div>

                <!-- Auction Card 4 -->
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="../../Assets/Cow-Gang.jpg" alt="Livestock Image">
                    </div>
                    <div class="auction-details">
                        <h2 class="auction-title">Livestock Auction Title</h2>
                        <p class="auction-description">Brief description of the livestock being auctioned.</p>
                        <div class="bidding-info">
                            <p>Opening Bid: <span class="opening-bid">$500.00</span></p>
                            <p>Current Bid: <span class="current-bid">$850.00</span></p>
                        </div>
                        <div class="time-remaining">
                            <p>Time Remaining: 2d 5h 32m</p>
                        </div>
                        <a href="#" class="view-details-btn">View Full Details</a>
                        <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                    </div>
                </div>

                <!-- Auction Card 5 -->
                <div class="auction-card">
                    <div class="auction-image">
                        <img src="../../Assets/Goat-Gang.jpg" alt="Livestock Image">
                    </div>
                    <div class="auction-details">
                        <h2 class="auction-title">Another Auction Title</h2>
                        <p class="auction-description">This is a brief description for another auction item.</p>
                        <div class="bidding-info">
                            <p>Opening Bid: <span class="opening-bid">$750.00</span></p>
                            <p>Current Bid: <span class="current-bid">$1,000.00</span></p>
                        </div>
                        <div class="time-remaining">
                            <p>Time Remaining: 1d 3h 12m</p>
                        </div>
                        <a href="#" class="view-details-btn">View Full Details</a>
                        <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

<script src="../../js/logout-confirmation.js"></script>
</html>
