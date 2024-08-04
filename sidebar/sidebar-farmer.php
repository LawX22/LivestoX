<div class="sidebar">
            <div class="profile-section">
                <div class="profile-image"></div>
                
                <div class="profile-name"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></div>
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
                        <a href="inbox.php" class="nav-button-link">
                            <button class="nav-button
                            <?php if ($page == 'inbox') {echo 'active';} ?>" href="../Frontend/Farmer/inbox.php">
                            <i class="fas fa-inbox"></i> Inbox
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
                            <i class="fas fa-comments"></i> Open Forum
                            </button>
                        </a>
                    </li>
                </ul>
            </nav>
            <button class="create-listing-button">Livestock Post</button>
            <div class="filters">
                <h3>SEARCH FILTER</h3>
                <h5>By Category</h5>
                <div class="filter-categories">
                    <label>
                        <input type="checkbox" name="category" value="cows">
                        Cows
                    </label>
                    <label>
                        <input type="checkbox" name="category" value="pigs">
                        Pigs
                    </label>
                    <label>
                        <input type="checkbox" name="category" value="chickens">
                        Chickens
                    </label>
                    <label>
                        <input type="checkbox" name="category" value="goats">
                        Goats
                    </label>
                    <label>
                        <input type="checkbox" name="category" value="turkey">
                        Turkey
                    </label>
                    <button class="confirm-button">FILTER</button>
                </div>
            </div>

            <div class="logout">
                <a href="../../Backend/login/logout.php" id="logout-link">
                    <button type="button" class="logout-button" onclick="confirmLogout(event)">LOG OUT</button>
                </a>
            </div>
            
        </div>