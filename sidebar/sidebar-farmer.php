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
                        <a href="dashboard.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'dashboard') {echo 'active';} ?>" href="../Frontend/Farmer/dashboard.php">
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
                            <?php if ($page == 'inbox') {echo 'active';} ?>" href="../Frontend/Farmer/message.php">
                            <i class="fas fa-inbox"></i> Message 
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="sellhistory.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'sellhistory') {echo 'active';} ?>" href="../Frontend/Farmer/sellhistory.php">
                            <i class="fas fa-history"></i> Sell History
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="openforum.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'openforum') {echo 'active';} ?>" href="../Frontend/Farmer/openforum.php">
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