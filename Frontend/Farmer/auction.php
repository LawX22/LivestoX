<?php
session_start();
include('../../Backend/db/db_connect.php');

// Redirect if the user is not logged in or is not a farmer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch the logged-in user's full details
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

// Check if the profile picture exists
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}

// Fetch existing auctions for the logged-in farmer
$auctionsQuery = "SELECT * FROM livestock_auctions WHERE farmer_id = ? ORDER BY date_posted DESC";
$stmt = mysqli_prepare($con, $auctionsQuery);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$auctionsResult = mysqli_stmt_get_result($stmt);
$auctions = mysqli_fetch_all($auctionsResult, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <button class="create-auction-btn" onclick="openModal('createAuctionModal')">Create Auction</button>
            </header>

            <!-- Auction Cards List -->
            <div class="auction-list">
                <?php foreach ($auctions as $auction): ?>
                    <div class="auction-card">
                        <div class="auction-image">
                            <img src="../../uploads/livestock_auctions/<?php echo htmlspecialchars($auction['image_posts']); ?>" alt="Livestock Image">
                        </div>
                        <div class="auction-details">
                            <h2 class="auction-title"><?php echo htmlspecialchars($auction['title']); ?></h2>
                            <p class="auction-description"><?php echo htmlspecialchars($auction['description']); ?></p>
                            <div class="bidding-info">
                                <p>Starting Bid: <span class="opening-bid">₱<?php echo number_format($auction['starting_price'], 2); ?></span></p>
                                <p>Current Bid: <span class="current-bid">₱<?php echo number_format($auction['current_highest_bid'], 2); ?></span></p>
                            </div>
                            <div class="time-remaining">
                                <p>Status: <?php echo htmlspecialchars($auction['status']); ?></p>
                            </div>
                            <button class="view-details-btn" onclick="openModal('modal<?php echo $auction['id']; ?>')">View Full Details</button>
                            <button class="save-button"><i class="fas fa-heart"></i> Save</button>
                        </div>

                        <div class="time-remaining">
                            <span id="countdown<?php echo $auction['id']; ?>"
                                data-end-time="<?php echo $auction['end_time']; ?>"
                                data-start-time="<?php echo $auction['start_time']; ?>">
                                Calculating...
                            </span>
                        </div>
                    </div>

                    <!-- Modal for each Auction -->
                    <div id="modal<?php echo $auction['id']; ?>" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2><?php echo htmlspecialchars($auction['title']); ?> - Full Details</h2>

                            <!-- Livestock Details Section -->
                            <div class="livestock-details">
                                <div class="image-details-container">
                                    <img src="../../uploads/livestock_auctions/<?php echo htmlspecialchars($auction['image_posts']); ?>" alt="Livestock Image">
                                </div>
                                <div class="details-text">
                                    <p><strong>Breed:</strong> <?php echo htmlspecialchars($auction['breed']); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($auction['quantity']); ?></p>
                                    <p><strong>Health Status:</strong> <?php echo htmlspecialchars($auction['health_status']); ?></p>
                                    <p><strong>Location:</strong> <?php echo htmlspecialchars($auction['location']); ?></p>
                                    <p><strong>Age:</strong> <?php echo htmlspecialchars($auction['age']); ?></p>
                                    <p><strong>Weight:</strong> <?php echo htmlspecialchars($auction['weight']); ?> kg</p>
                                    <p><strong>Starting Bid:</strong> ₱<?php echo number_format($auction['starting_price'], 2); ?></p>
                                    <p><strong>Current Bid:</strong> ₱<?php echo number_format($auction['current_highest_bid'], 2); ?></p>
                                    <p><strong>Auction Start:</strong> <?php echo date('F d, Y h:i A', strtotime($auction['start_time'])); ?></p>
                                    <p><strong>Auction End:</strong> <?php echo date('F d, Y h:i A', strtotime($auction['end_time'])); ?></p>
                                </div>
                            </div>

                            <!-- Bidding Section -->
                            <div class="bidding-section">
                                <button class="view-bids-btn" onclick="openBiddingModal('biddingModal<?php echo $auction['id']; ?>')">View Bidders</button>
                            </div>
                        </div>
                    </div>

                    <!-- Bidding Modal -->
                    <div id="biddingModal<?php echo $auction['id']; ?>" class="modal">
                        <div class="auction-modal-content">
                            <span class="close">&times;</span>
                            <h2>Bidders for <?php echo htmlspecialchars($auction['title']); ?></h2>

                            <!-- Bidders List Section -->
                            <div class="bidders-list">
                                <div class="bidders-header">
                                    <div class="bidders-column">Rank</div>
                                    <div class="bidders-column">Bidder</div>
                                    <div class="bidders-column">Bid Amount</div>
                                    <div class="bidders-column">Date & Time</div>
                                </div>
                                <!-- Placeholder for bidders -->
                                <p class="no-bidders">No bids yet for this auction.</p>
                            </div>

                            <button class="back-btn" onclick="goBackToDetails('modal<?php echo $auction['id']; ?>')">Back to Full Details</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Create Auction Modal -->
    <div id="createAuctionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('createAuctionModal')">&times;</span>
            <h2>Create New Livestock Auction</h2>

            <form id="create-auction-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Auction Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="livestock_type">Livestock Type</label>
                    <input type="text" id="livestock_type" name="livestock_type" required>
                </div>
                <div class="form-group">
                    <label for="breed">Breed</label>
                    <input type="text" id="breed" name="breed" required>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="text" id="age" name="age" required>
                </div>
                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="number" id="weight" name="weight" required>
                </div>
                <div class="form-group">
                    <label for="health_status">Health Status</label>
                    <input type="text" id="health_status" name="health_status" required>
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" required>
                </div>
                <div class="form-group">
                    <label for="starting_price">Starting Price</label>
                    <input type="number" id="starting_price" name="starting_price" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="datetime-local" id="start_time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="datetime-local" id="end_time" name="end_time" required>
                </div>
                <div class="form-group">
                    <label for="image_posts">Auction Image</label>
                    <input type="file" id="image_posts" name="image_posts" accept="image/*" required>
                </div>
                <button type="submit" class="submit-auction-btn">Create Auction</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../js/auction/create-auction.js"></script>
    <script src="../../js/auction/modal.js"></script>
    <script src="../../js/logout-confirmation.js"></script>
    <script src="../../js/auction/countdown.js"></script>
</body>

</html>