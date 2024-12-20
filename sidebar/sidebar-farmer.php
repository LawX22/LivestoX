<!-- Sidebar Section -->
<div class="sidebar">
    <div class="sidebar_content">
        <div class="livestock-logo">
            <img src="../../Assets/LivestoX_Logo.png" alt="Livestock Logo" class="livestock-img">

        </div>

        <a href="../../Frontend/Pages/users-profile-page.php?user_id=<?php echo urlencode($user_id); ?>" class="profile-section-link">
            <div class="profile-section">
                <div class="profile-image">
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
                </div>
                <div class="profile-name">
                    <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>
                    <p class="user-type"><?php echo htmlspecialchars(ucfirst($user_type)); ?></p>
                </div>
            </div>
        </a>



        <nav>
            <ul>
                <li>
                    <a href="browse_livestock" class="nav-button-link">
                        <button class="nav-button <?php if ($page == 'browse_livestock') {
                                                        echo 'active';
                                                    } ?>">
                            <i class="fas fa-globe"></i> Browse Livestock
                        </button>
                    </a>
                </li>
                <li>
                    <a href="auction" class="nav-button-link">
                        <button class="nav-button <?php if ($page == 'auction') {
                                                        echo 'active';
                                                    } ?>">
                            <i class="fas fa-gavel"></i> Auction Livestock
                        </button>
                    </a>
                </li>
                <li>
                    <a href="message" class="nav-button-link">
                        <button class="nav-button <?php if ($page == 'message') {
                                                        echo 'active';
                                                    } ?>">
                            <i class="fas fa-inbox"></i> Message
                        </button>
                    </a>
                </li>
                <li>
                    <a href="seller_dashboard" class="nav-button-link">
                        <button class="nav-button <?php if ($page == 'seller_dashboard') {
                                                        echo 'active';
                                                    } ?>">
                            <i class="fas fa-history"></i> Seller Dashboard
                        </button>
                    </a>
                </li>
                <li>
                    <a href="livestock_forum" class="nav-button-link">
                        <button class="nav-button <?php if ($page == 'livestock_forum') {
                                                        echo 'active';
                                                    } ?>">
                            <i class="fas fa-comments"></i> Livestock Forum
                        </button>
                    </a>
                </li>
            </ul>
        </nav>

       


        <!-- Livestock Update Modal -->
        <div id="updateLivestockModal" class="modal-choose">
            <div class="modal-content-chs">
                <span class="close" onclick="closeModal('updateLivestockModal')">&times;</span>
                <h2>Update Livestock</h2>
                <form id="updateLivestockForm" action="../../Backend/livestock_posts/update_post.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="update-post-id" name="post_id" required> <!-- Hidden field for post ID -->

                    <label for="update-livestock-title">Title:</label>
                    <input type="text" id="update-livestock-title" name="title" placeholder="Enter Livestock Title" required>

                    <label for="update-livestock-description">Description:</label>
                    <textarea id="update-livestock-description" name="description" placeholder="Describe the livestock" required></textarea>

                    <label for="update-livestock-type">Type:</label>
                    <select id="update-livestock-type" name="livestock_type" required>
                        <option value="Other">Choose Livestock</option>
                        <option value="COW">COW</option>
                        <option value="SHEEP">SHEEP</option>
                        <option value="GOAT">GOAT</option>
                        <option value="CHICKEN">CHICKEN</option>
                        <option value="PIG">PIG</option>
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

                    <button type="submit" id="updateLivestockButton">Update Livestock</button>
                </form>
            </div>
        </div>




        <div class="noti-box">
            <p>Notification here</p>
        </div>

        <div class="logout">
            <button type="button" class="logout-button" id="logout-button">LOG OUT</button>
        </div>
    </div>
</div>

<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/livestock/open_modal.js"></script>
<script>
    // Function to open the modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
        }
    }

    // Function to close the modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "none";
        }
    }

    // Close modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('livestockModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };
</script>