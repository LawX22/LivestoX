<?php
session_start();
include('../../Backend/db/db_connect.php');

// Redirect if user is not logged in or is not a buyer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'buyer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch the logged-in user's details
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
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}

// Fetch all active auctions with farmer information
$auctionsQuery = "
    SELECT 
        la.*,
        CONCAT(tu.first_name, ' ', tu.last_name) as farmer_name,
        tu.profile_picture as farmer_profile
    FROM 
        livestock_auctions la
    JOIN 
        tbl_users tu ON la.farmer_id = tu.id
    WHERE 
        la.status = 'active' AND
        la.end_time > NOW()
    ORDER BY 
        la.date_posted DESC
";
$auctionsResult = mysqli_query($con, $auctionsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Browse Auctions</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/buyer/buyer_auction.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="../../css/browse-auctions.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php
        $page = 'browse_auctions';
        include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
                <div class="search">
                    <input type="text" id="searchAuctions" placeholder="Search Auctions">
                </div>
            </header>

            <!-- Auction Cards List -->
            <div class="auction-list">
                <?php while ($auction = mysqli_fetch_assoc($auctionsResult)): ?>
                    <div class="auction-card" data-auction-id="<?php echo $auction['id']; ?>">
                        <div class="auction-image">
                            <img src="../../uploads/livestock_auctions/<?php echo htmlspecialchars($auction['image_posts']); ?>" alt="Livestock Image">
                        </div>
                        <div class="auction-details">
                            <div class="farmer-info">
                                <img src="../../uploads/profile_pictures/<?php echo !empty($auction['farmer_profile']) ? htmlspecialchars($auction['farmer_profile']) : 'default-profile.png'; ?>" 
                                     alt="Farmer Profile" class="farmer-profile-pic">
                                <span class="farmer-name"><?php echo htmlspecialchars($auction['farmer_name']); ?></span>
                            </div>
                            <h2 class="auction-title"><?php echo htmlspecialchars($auction['title']); ?></h2>
                            <p class="auction-description"><?php echo htmlspecialchars($auction['description']); ?></p>
                            <div class="bidding-info">
                                <p>Starting Bid: <span class="opening-bid">₱<?php echo number_format($auction['starting_price'], 2); ?></span></p>
                                <p>Current Bid: <span class="current-bid">₱<?php echo number_format($auction['current_highest_bid'] ?: $auction['starting_price'], 2); ?></span></p>
                            </div>
                            <div class="time-remaining">
                                <span id="countdown<?php echo $auction['id']; ?>"
                                    data-end-time="<?php echo $auction['end_time']; ?>"
                                    data-start-time="<?php echo $auction['start_time']; ?>">
                                    Calculating...
                                </span>
                            </div>
                            <button class="view-details-btn" onclick="openModal('modal<?php echo $auction['id']; ?>')">View Details</button>
                            <button class="save-button" data-auction-id="<?php echo $auction['id']; ?>">
                                <i class="fas fa-heart"></i> Save
                            </button>
                        </div>
                    </div>

                    <!-- Auction Details Modal -->
                    <div id="modal<?php echo $auction['id']; ?>" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><?php echo htmlspecialchars($auction['title']); ?> - Full Details</h2>

                            <div class="livestock-details">
                                <div class="image-details-container">
                                    <img src="../../uploads/livestock_auctions/<?php echo htmlspecialchars($auction['image_posts']); ?>" alt="Livestock Image">
                                </div>
                                <div class="details-text">
                                    <div class="farmer-info-modal">
                                        <img src="../../uploads/profile_pictures/<?php echo !empty($auction['farmer_profile']) ? htmlspecialchars($auction['farmer_profile']) : 'default-profile.png'; ?>" 
                                             alt="Farmer Profile" class="farmer-profile-pic">
                                        <span class="farmer-name"><?php echo htmlspecialchars($auction['farmer_name']); ?></span>
                                    </div>
                                    <p><strong>Breed:</strong> <?php echo htmlspecialchars($auction['breed']); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($auction['quantity']); ?></p>
                                    <p><strong>Health Status:</strong> <?php echo htmlspecialchars($auction['health_status']); ?></p>
                                    <p><strong>Location:</strong> <?php echo htmlspecialchars($auction['location']); ?></p>
                                    <p><strong>Age:</strong> <?php echo htmlspecialchars($auction['age']); ?></p>
                                    <p><strong>Weight:</strong> <?php echo htmlspecialchars($auction['weight']); ?> kg</p>
                                    <p><strong>Starting Bid:</strong> ₱<?php echo number_format($auction['starting_price'], 2); ?></p>
                                    <p><strong>Current Bid:</strong> ₱<?php echo number_format($auction['current_highest_bid'] ?: $auction['starting_price'], 2); ?></p>
                                    <p><strong>Auction Start:</strong> <?php echo date('F d, Y h:i A', strtotime($auction['start_time'])); ?></p>
                                    <p><strong>Auction End:</strong> <?php echo date('F d, Y h:i A', strtotime($auction['end_time'])); ?></p>
                                </div>
                            </div>

                            <!-- Bidding Section -->
                            <div class="bidding-section">
                                <form class="bid-form" data-auction-id="<?php echo $auction['id']; ?>">
                                    <div class="bid-input-group">
                                        <label for="bid-amount-<?php echo $auction['id']; ?>">Your Bid Amount (₱):</label>
                                        <input type="number" 
                                               id="bid-amount-<?php echo $auction['id']; ?>" 
                                               name="bid_amount" 
                                               min="<?php echo ($auction['current_highest_bid'] ?: $auction['starting_price']) + 1; ?>"
                                               step="1"
                                               required>
                                    </div>
                                    <button type="submit" class="place-bid-btn">Place Bid</button>
                                </form>
                                <button class="view-bids-btn" onclick="openBiddingModal('biddingModal<?php echo $auction['id']; ?>')">View All Bids</button>
                            </div>
                        </div>
                    </div>

                    <!-- Bidding History Modal -->
                    <div id="biddingModal<?php echo $auction['id']; ?>" class="modal">
                        <div class="auction-modal-content">
                            <span class="close">&times;</span>
                            <h2>Bid History - <?php echo htmlspecialchars($auction['title']); ?></h2>
                            
                            <div class="bidders-list">
                                <div class="bidders-header">
                                    <div class="bidders-column">Rank</div>
                                    <div class="bidders-column">Bidder</div>
                                    <div class="bidders-column">Bid Amount</div>
                                    <div class="bidders-column">Date & Time</div>
                                </div>
                                <div id="bidders-data-<?php echo $auction['id']; ?>" class="bidders-data">
                                    <!-- Bid history will be loaded here via AJAX -->
                                    <p class="no-bidders">Loading bids...</p>
                                </div>
                            </div>
                            
                            <button class="back-btn" onclick="goBackToDetails('modal<?php echo $auction['id']; ?>')">Back to Details</button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/auction/modal.js"></script>
    <script src="../../js/auction/countdown.js"></script>
    <script src="../../js/auction/bidding.js"></script>
    <script src="../../js/logout-confirmation.js"></script>
</body>
</html>