<div class="sidebar">
<div class="livestock-logo">
            <img src="../../Assets/LivestoX_Logo.png" alt="Livestock Logo" class="livestock-img">

        </div>
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
                <a href="admin_dashboard.php" class="nav-button-link">
                    <button class="nav-button
                                <?php if ($page == 'admin_dashboard') {
                                    echo 'active';
                                } ?>" href="../Frontend/Admin/admin_dashboard.php">
                        <i class="fas fa-globe"></i> Dashboard
                    </button>
                </a>
            </li>
            <li>
                <a href="user_management.php" class="nav-button-link">
                    <button class="nav-button
                                <?php if ($page == 'user_management.php') {
                                    echo 'active';
                                } ?>" href="../Frontend/Admin/user_management.php">
                        <i class="fas fa-globe"></i> User Management
                    </button>
                </a>
            </li>
            <!-- <li>
                        <a href="reports.php" class="nav-button-link">
                            <button class="nav-button
                                <?php if ($page == 'reports') {
                                    echo 'active';
                                } ?>" href="../Frontend/Admin/reports.php">
                                <i class="fas fa-globe"></i> Reports
                            </button>
                        </a>
                    </li> -->
            <li>
                <a href="browse_livestock.php" class="nav-button-link">
                    <button class="nav-button
                                <?php if ($page == 'browse_livestock') {
                                    echo 'active';
                                } ?>" href="../Frontend/Admin/browse_livestock.php">
                        <i class="fas fa-globe"></i> Browse Livestock
                    </button>
                </a>
            </li>
            <li>
                <a href="auction.php" class="nav-button-link">
                    <button class="nav-button 
                                <?php if ($page == 'auction') {
                                    echo 'active';
                                } ?>" href="../Frontend/Admin/auction.php">
                        <i class="fas fa-gavel"></i> Auction Livestock
                    </button>
                </a>
            </li>
            <li>
                <a href="livestock_forum.php" class="nav-button-link">
                    <button class="nav-button
                            <?php if ($page == 'livestock_forum') {
                                echo 'active';
                            } ?>" href="../Frontend/Admin/livestock_forum.php">
                        <i class="fas fa-comments"></i> Livestock Forum</button>
                </a>
            </li>
        </ul>
    </nav>

    <div class="logout">
        <button type="button" class="logout-button" id="logout-button">LOG OUT</button>
    </div>

</div>