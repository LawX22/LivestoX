<div class="sidebar">
            <div class="profile-section">
                <div class="profile-image">
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Image">
                </div>
                <div class="profile-name">
                    <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>
                    <p class="user-type"><?php echo htmlspecialchars(ucfirst($user_type)); ?></p> <!-- User type added here -->
                </div>
            </div>

            <nav>
                <ul>
                    <li>
                        <a href="browse_livestock.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'browse_livestock') {echo 'active';} ?>" href="../Frontend/Farmer/browse_livestock.php">
                            <i class="fas fa-globe"></i>  Browse Livestock
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="auction.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'auction') {echo 'active';} ?>" href="../Frontend/Farmer/auction.php">
                            <i class="fas fa-gavel"></i> Auction Livestock
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="message.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'message') {echo 'active';} ?>" href="../Frontend/Farmer/message.php">
                            <i class="fas fa-inbox"></i> Message 
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="seller_dashboard.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'seller_dashboard') {echo 'active';} ?>" href="../Frontend/Farmer/seller_dashboard.php">
                            <i class="fas fa-history"></i> Seller Dashboard
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="livestock_forum.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'livestock_forum') {echo 'active';} ?>" href="../Frontend/Farmer/livestock_forum.php">
                            <i class="fas fa-comments"></i> Livestock Forum
                            </button>
                        </a>
                    </li>
                </ul>
            </nav>
            <button class="create-listing-button">Livestock Post</button>

            <div class="noti-box">
                <p>Notification here</p>
            </div>

            <div class="logout">
                <a href="../../Backend/login/logout.php" id="logout-link">
                    <button type="button" class="logout-button" onclick="confirmLogout(event)">LOG OUT</button>
                </a>
            </div>
            
        </div>