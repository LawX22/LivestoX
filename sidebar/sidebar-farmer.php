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
    <button class="create-listing-button" onclick="openModal('createListingModal')">
        <i class="fas fa-plus-circle"></i> Post Livestocks
    </button>

    <!-- Modal for Post Livestock or Auction -->
    <div id="createListingModal" class="modal-choose">
        <div class="modal-content-chs">
            <span class="close" onclick="closeModal('createListingModal')">&times;</span>
            <h2>Choose Post Type</h2>
            <div class="card-container">
                <div class="choose-card" onclick="openPostModal('livestockModal')">
                <i class="fas fa-paw"></i>
                    <h3>Livestock</h3>
                    <p>Create listings for different types of Livestocks</p>
                </div>
                <div class="choose-card" onclick="openPostModal('auctionModal')">
                    <i class="fas fa-gavel"></i>
                    <h3>Auction</h3>
                    <p>Post Livestock Auction for buyers to bid</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Modal -->
    <div id="livestockModal" class="modal-choose">
        <div class="modal-content-chs">
            <span class="close" onclick="closeModal('livestockModal')">&times;</span>
            <h2>Post Livestock</h2>
            <form action="submit_livestock.php" method="POST">
                <label for="livestock-title">Title:</label>
                <input type="text" id="livestock-title" name="title" placeholder="Enter Livestock Title" required>
                
                <label for="livestock-description">Description:</label>
                <textarea id="livestock-description" name="description" placeholder="Describe the livestock" required></textarea>
                
                <label for="livestock-price">Price:</label>
                <input type="number" id="livestock-price" name="price" placeholder="Enter Price" required>
                
                <label for="livestock-category">Category:</label>
                <select id="livestock-category" name="category" required>
                    <option value="cattle">Cattle</option>
                    <option value="sheep">Sheep</option>
                    <option value="goats">Goats</option>
                    <option value="poultry">Poultry</option>
                </select>

                <button type="submit">Submit Livestock</button>
            </form>
        </div>
    </div>

    <!-- Auction Modal -->
    <div id="auctionModal" class="modal-choose">
        <div class="modal-content-chs">
            <span class="close" onclick="closeModal('auctionModal')">&times;</span>
            <h2>Post Auction</h2>
            <form action="submit_auction.php" method="POST">
                <label for="auction-title">Title:</label>
                <input type="text" id="auction-title" name="title" placeholder="Enter Auction Title" required>
                
                <label for="auction-description">Description:</label>
                <textarea id="auction-description" name="description" placeholder="Describe the auction" required></textarea>
                
                <label for="auction-starting-price">Starting Price:</label>
                <input type="number" id="auction-starting-price" name="starting_price" placeholder="Enter Starting Price" required>
                
                <label for="auction-end-date">Auction End Date:</label>
                <input type="date" id="auction-end-date" name="end_date" required>

                <label for="auction-category">Category:</label>
                <select id="auction-category" name="category" required>
                    <option value="cattle">Cattle</option>
                    <option value="sheep">Sheep</option>
                    <option value="goats">Goats</option>
                    <option value="poultry">Poultry</option>
                </select>

                <button type="submit">Submit Auction</button>
            </form>
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
    function openModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    // Close Modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    // Open specific Post Modal (Livestock or Auction)
    function openPostModal(postModalId) {
        closeModal('createListingModal'); // Close the initial modal
        openModal(postModalId); // Open the respective post modal
    }
</script>