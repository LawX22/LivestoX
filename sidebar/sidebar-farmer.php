<!-- Sidebar Section -->
<div class="sidebar">
    <div class="profile-section">
        <div class="profile-image">
            <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
        </div>
        <div class="profile-name">
            <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>
            <p class="user-type"><?php echo htmlspecialchars(ucfirst($user_type)); ?></p>
        </div>
    </div>

    <nav>
        <ul>
            <li>
                <a href="browse_livestock.php" class="nav-button-link">
                    <button class="nav-button <?php if ($page == 'browse_livestock') {echo 'active';} ?>">
                        <i class="fas fa-globe"></i> Browse Livestock
                    </button>
                </a>
            </li>
            <li>
                <a href="auction.php" class="nav-button-link">
                    <button class="nav-button <?php if ($page == 'auction') {echo 'active';} ?>">
                        <i class="fas fa-gavel"></i> Auction Livestock
                    </button>
                </a>
            </li>
            <li>
                <a href="message.php" class="nav-button-link">
                    <button class="nav-button <?php if ($page == 'message') {echo 'active';} ?>">
                        <i class="fas fa-inbox"></i> Message
                    </button>
                </a>
            </li>
            <li>
                <a href="seller_dashboard.php" class="nav-button-link">
                    <button class="nav-button <?php if ($page == 'seller_dashboard') {echo 'active';} ?>">
                        <i class="fas fa-history"></i> Seller Dashboard
                    </button>
                </a>
            </li>
            <li>
                <a href="livestock_forum.php" class="nav-button-link">
                    <button class="nav-button <?php if ($page == 'livestock_forum') {echo 'active';} ?>">
                        <i class="fas fa-comments"></i> Livestock Forum
                    </button>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Create Listing Button with Icon -->
    <button class="create-listing-button" onclick="openModal()">
        <i class="fas fa-plus-circle"></i> Post Livestocks
    </button>

    <!-- Modal for Post Livestock or Auction -->
    <div id="createListingModal" class="modal-choose">
        <div class="modal-content-chs">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Choose Post Type</h2>
            <div class="card-container">
                <div class="choose-card" onclick="postLivestock()">
                    <i class="fas fa-cow"></i>
                    <h3>Livestock</h3>
                    <p>Create listings for different type of Livestocks</p>
                </div>
                <div class="choose-card" onclick="postAuction()">
                    <i class="fas fa-gavel"></i>
                    <h3>Auction</h3>
                    <p>Post Livestock Auction for buyers to bid</p>
                </div>
            </div>
        </div>
    </div>

    <div class="noti-box">
        <p>Notification here</p>
    </div>

    <div class="logout">
        <a href="../../Backend/login/logout.php" id="logout-link">
            <button type="button" class="logout-button" onclick="confirmLogout(event)">LOG OUT</button>
        </a>
    </div>
</div>

<script>
    // Open Modal
    function openModal() {
        document.getElementById("createListingModal").style.display = "block";
    }

    // Close Modal
    function closeModal() {
        document.getElementById("createListingModal").style.display = "none";
    }

    // Post Livestock Function
    function postLivestock() {
        window.location.href = "post_livestock.php"; // Change to your livestock posting URL
    }

    // Post Auction Function
    function postAuction() {
        window.location.href = "post_auction.php"; // Change to your auction posting URL
    }
</script>

