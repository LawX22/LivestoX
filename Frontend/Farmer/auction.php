<?php
session_start();
include('../../Backend/db/db_connect.php'); 

// Redirect if the user is not logged in or is not a buyer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch the logged-in user's full details including profile picture and user type
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

// Check if the profile picture exists and file exists on server
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}

// Fetch all buyer users (if needed for display)
$sql1 = "SELECT * FROM tbl_users WHERE user_type = 'farmer'";
$result1 = mysqli_query($con, $sql1);

// Fetch posts with associated user information
$postQuery = "SELECT f.id, f.title, f.description, f.image, f.created_at, f.user_id, u.first_name, u.last_name, u.user_type 
              FROM forum f 
              JOIN tbl_users u ON f.user_id = u.id 
              ORDER BY f.created_at DESC";
$postResult = mysqli_query($con, $postQuery);
$posts = mysqli_fetch_all($postResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Auction Page</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/auction.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <?php 
        $page = 'auction';
        include('../../sidebar/sidebar-farmer.php');
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
                        <p>Opening Bid: <span class="opening-bid">₱500.00</span></p>
                        <p>Current Bid: <span class="current-bid">₱850.00</span></p>
                    </div>
                    <div class="time-remaining">
                        <p>Time Remaining: 2d 5h 32m</p>
                    </div>
                    <button class="view-details-btn" onclick="openModal('modal1')">View Full Details</button>
                    <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                </div>
            </div>

            <!-- Modal 1 -->
            <div id="modal1" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modal1')">&times;</span>
                    <h2>Livestock Auction Title - Full Details</h2>
                    
                    <!-- Livestock Details Section -->
                    <div class="livestock-details">
                        <p><strong>Breed:</strong> Holstein Dairy Cattle</p>
                        <p><strong>Quantity:</strong> 20 Cows</p>
                        <p><strong>Health:</strong> Excellent (Vet checked, vaccinated)</p>
                        <p><strong>Production:</strong> High yield, strong milk production history</p>
                        <p><strong>Starting Bid:</strong> ₱1,500</p>
                        <p><strong>Current Bid:</strong> ₱2,100</p>
                    </div>

                    <!-- Farmer Info Section -->
                    <div class="farmer-info">
                        <h3>Farmer Details</h3>
                        <div class="farmer-profile">
                            <div class="profile-image">
                                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
                            </div>
                            <div>
                                <p><strong>Name:</strong> Lawrenz Carisusa</p>
                                <p><strong>Location:</strong> Taytayan, Bogo</p>
                                <p><strong>Experience:</strong> 15 years in dairy farming</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bidding Section -->
                    <div class="bidding-section">
                        <button class="view-bids-btn" onclick="openBiddingModal('biddingModal1')">View Bidders</button>
                    </div>
                </div>
            </div>

            <!-- Bidding Modal 1 -->
            <div id="biddingModal1" class="modal">
                <div class="auction-modal-content">
                    <span class="close" onclick="closeModal('biddingModal1')">&times;</span>
                    <h2>Bidders for Livestock Auction</h2>

                    <!-- Bidders List Section -->
                    <div class="bidders-list">
                        <div class="bidders-header">
                            <div class="bidders-column">Rank</div>
                            <div class="bidders-column">Bidder</div>
                            <div class="bidders-column">Bid Amount</div>
                            <div class="bidders-column">Date & Time</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">1</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/Livestock.jpg" alt="Livestock Image" class="bidders-profile">
                                    <strong>John Doe</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱950.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:15 PM</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">2</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/Cow-Gang.jpg" alt="Livestock Image" class="bidders-profile">
                                    <strong>Jane Smith</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱900.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:30 PM</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">3</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/default-profile.png" alt="Livestock Image" class="bidders-profile">
                                    <strong>Michael Johnson</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱850.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:45 PM</div>
                        </div>
                        <!-- Add more bidders as needed -->
                    </div>

                    <button class="back-btn" onclick="goBackToDetails('modal1')">Back to Full Details</button>
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
                        <p>Opening Bid: <span class="opening-bid">₱750.00</span></p>
                        <p>Current Bid: <span class="current-bid">₱1,000.00</span></p>
                    </div>
                    <div class="time-remaining">
                        <p>Time Remaining: 1d 3h 12m</p>
                    </div>
                    <button class="view-details-btn" onclick="openModal('modal2')">View Full Details</button>
                    <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                </div>
            </div>

            <!-- Modal 2 -->
            <div id="modal2" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('modal2')">&times;</span>
                    <h2>Another Auction Title - Full Details</h2>
                    
                    <!-- Livestock Details Section -->
                    <div class="livestock-details">
                        <p><strong>Breed:</strong> Boer Goat</p>
                        <p><strong>Quantity:</strong> 15 Goats</p>
                        <p><strong>Health:</strong> Good (Recent vaccinations)</p>
                        <p><strong>Production:</strong> Known for meat quality</p>
                        <p><strong>Starting Bid:</strong> ₱1,000</p>
                        <p><strong>Current Bid:</strong> ₱1,500</p>
                    </div>

                    <!-- Farmer Info Section -->
                    <div class="farmer-info">
                        <h3>Farmer Details</h3>
                        <div class="farmer-profile">
                            <div class="profile-image">
                                <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
                            </div>
                            <div>
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                                <p><strong>Location:</strong> San Remigio, Cebu</p>
                                <p><strong>Experience:</strong> 10 years in goat farming</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bidding Section -->
                    <div class="bidding-section">
                        <button class="view-bids-btn" onclick="openBiddingModal('biddingModal2')">View Bidders</button>
                    </div>
                </div>
            </div>

            <!-- Bidding Modal 2 -->
            <div id="biddingModal2" class="modal">
                <div class="auction-modal-content">
                    <span class="close" onclick="closeModal('biddingModal2')">&times;</span>
                    <h2>Bidders for Livestock Auction</h2>

                    <!-- Bidders List Section -->
                    <div class="bidders-list">
                        <div class="bidders-header">
                            <div class="bidders-column">Rank</div>
                            <div class="bidders-column">Bidder</div>
                            <div class="bidders-column">Bid Amount</div>
                            <div class="bidders-column">Date & Time</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">1</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/Livestock.jpg" alt="Livestock Image" class="bidders-profile">
                                    <strong>John Doe</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱950.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:15 PM</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">2</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/Cow-Gang.jpg" alt="Livestock Image" class="bidders-profile">
                                    <strong>Jane Smith</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱900.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:30 PM</div>
                        </div>
                        <div class="bidder-row">
                            <div class="bidders-column">3</div>
                            <div class="bidders-column">
                                <div class="bidder-profile">
                                    <img src="../../Assets/default-profile.png" alt="Livestock Image" class="bidders-profile">
                                    <strong>Michael Johnson</strong>
                                </div>
                            </div>
                            <div class="bidders-column bid-amount">₱850.00</div>
                            <div class="bidders-column bid-time">October 14, 2024 12:45 PM</div>
                        </div>
                        <!-- Add more bidders as needed -->
                    </div>

                    <button class="back-btn" onclick="goBackToDetails('modal1')">Back to Full Details</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/auction/modal.js"></script>


</html>