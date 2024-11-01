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
        <i class="fas fa-plus-circle"></i> Post Livestock
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
                    <p>Create listings for different types of livestock</p>
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
                <form action="../../Backend/livestock_posts/add_post.php" method="POST" enctype="multipart/form-data">
                <label for="livestock-title">Title:</label>
                <input type="text" id="livestock-title" name="title" placeholder="Enter Livestock Title" required>
                
                <label for="livestock-description">Description:</label>
                <textarea id="livestock-description" name="description" placeholder="Describe the livestock" required></textarea>
                
                <label for="livestock-type">Type:</label>
                <select id="livestock-type" name="livestock_type" required>
                    <option value="Other">Choose Livestock</option>
                    <option value="Cattle">Cow</option>
                    <option value="Sheep">Sheep</option>
                    <option value="Goat">Goat</option>
                    <option value="Poultry">Poultry</option>
                    <option value="Pig">Pig</option>
                    <option value="Other">Other</option>
                </select>
                
                <label for="livestock-breed">Breed:</label>
                <input type="text" id="livestock-breed" name="breed" placeholder="Enter Breed">

                <label for="livestock-age">Age (in years):</label>
                <input type="number" step="0.01" id="livestock-age" name="age" placeholder="Enter Age">

                <label for="livestock-weight">Weight (kg):</label>
                <input type="number" step="0.01" id="livestock-weight" name="weight" placeholder="Enter Weight">

                <label for="livestock-health-status">Health Status:</label>
                <input type="text" id="livestock-health-status" name="health_status" placeholder="Enter Health Status">

                <label for="livestock-location">Location:</label>
                <input type="text" id="livestock-location" name="location" placeholder="Enter Location">

                <label for="livestock-price">Price:</label>
                <input type="number" step="0.01" id="livestock-price" name="price" placeholder="Enter Price" required>

                <label for="livestock-quantity">Quantity:</label>
                <input type="number" id="livestock-quantity" name="quantity" value="1" min="1" required>

                <label for="livestock-image">Upload Livestock Image :</label>
                <input type="file" id="livestock-image" name="image_posts">

                <button type="submit">Submit Livestock</button>
            </form>
        </div>
    </div>

   <!-- Update Livestock Modal -->
<div id="updateLivestockModal" class="modal-choose" style="display:none;"> <!-- Make sure the modal starts hidden -->
    <div class="modal-content-chs">
        <span class="close" onclick="closeModal('updateLivestockModal')">&times;</span>
        <h2>Update Livestock</h2>
        <form action="../../Backend/livestock_posts/update_post.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="update-livestock-id" name="post_id"> <!-- Hidden field for post ID -->

            <label for="update-livestock-title">Title:</label>
            <input type="text" id="update-livestock-title" name="title" placeholder="Enter Livestock Title" required>
            
            <label for="update-livestock-description">Description:</label>
            <textarea id="update-livestock-description" name="description" placeholder="Describe the livestock" required></textarea>
            
            <label for="update-livestock-type">Type:</label>
            <select id="update-livestock-type" name="livestock_type" required>
                <option value="Other">Choose Livestock</option>
                <option value="Cattle">Cow</option>
                <option value="Sheep">Sheep</option>
                <option value="Goat">Goat</option>
                <option value="Poultry">Poultry</option>
                <option value="Pig">Pig</option>
                <option value="Other">Other</option>
            </select>
            
            <label for="update-livestock-breed">Breed:</label>
            <input type="text" id="update-livestock-breed" name="breed" placeholder="Enter Breed">

            <label for="update-livestock-age">Age (in years):</label>
            <input type="number" step="0.01" id="update-livestock-age" name="age" placeholder="Enter Age">

            <label for="update-livestock-weight">Weight (kg):</label>
            <input type="number" step="0.01" id="update-livestock-weight" name="weight" placeholder="Enter Weight">

            <label for="update-livestock-health-status">Health Status:</label>
            <input type="text" id="update-livestock-health-status" name="health_status" placeholder="Enter Health Status">

            <label for="update-livestock-location">Location:</label>
            <input type="text" id="update-livestock-location" name="location" placeholder="Enter Location">

            <label for="update-livestock-price">Price:</label>
            <input type="number" step="0.01" id="update-livestock-price" name="price" placeholder="Enter Price" required>

            <label for="update-livestock-quantity">Quantity:</label>
            <input type="number" id="update-livestock-quantity" name="quantity" value="1" min="1" required>

            <label for="update-livestock-image">Upload New Livestock Image (optional):</label>
            <input type="file" id="update-livestock-image" name="image_posts">
            <small>Leave blank if you do not wish to change the image.</small> <!-- Instruction for image update -->

            <button type="submit">Update Livestock</button>
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
                    <option value="Cattle">Cattle</option>
                    <option value="Sheep">Sheep</option>
                    <option value="Goat">Goat</option>
                    <option value="Poultry">Poultry</option>
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

<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/livestock/open_modal.js"></script>
