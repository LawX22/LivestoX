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
                            <?php if ($page == 'dashboard') {echo 'active';} ?>" href="../Frontend/Buyer/dashboard.php">
                            <i class="fas fa-globe"></i>  Browse Livestock</button>
                        </a>
                    </li>
                    <li>
                        <a href="auction.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'auction') {echo 'active';} ?>" href="../Frontend/Buyer/auction.php">
                            <i class="fas fa-gavel"></i> Auction Livestock</button>
                        </a>
                    </li>
                    <li>
                        <a href="message.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'inbox') {echo 'active';} ?>" href="../Frontend/Buyer/message.php">
                            <i class="fas fa-inbox"></i> Message</button>  
                        </a>
                    </li>
                    <li>
                        <a href="buyhistory.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'buyhistory') {echo 'active';} ?>" href="../Frontend/Buyer/buyhistory.php">
                            <i class="fas fa-history"></i> Buy History</button>
                        </a>
                    </li>
                    <li>
                        <a href="openforum.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'openforum') {echo 'active';} ?>" href="../Frontend/Buyer/openforum.php">
                            <i class="fas fa-comments"></i> Livestock Forum</button>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="noti-box">
                <p>Notification here</p>
            </div>

            <div class="logout">
                <a href="../../Backend/login/logout.php" id="logout-link">
                    <button type="button" class="logout-button" onclick="confirmLogout(event)">LOG OUT</button>
                </a>
            </div>

        </div>