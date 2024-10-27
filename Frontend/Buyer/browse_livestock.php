<?php
    session_start();
    include('../../Backend/db/db_connect.php'); 

    if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'buyer') {
        header("Location: ../../Frontend/login.php");
        exit();
    }

    // Fetch user data including user_type
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
    $profile_image = !empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture) 
        ? '../../uploads/profile_pictures/' . $profile_picture 
        : $default_profile_picture;

    // Fetch livestock posts
    $livestock_posts_query = "SELECT lp.post_id, lp.title, lp.description, lp.price, lp.quantity, lp.image_posts, lp.date_posted, u.first_name AS farmer_first_name, u.last_name AS farmer_last_name 
                            FROM livestock_posts lp 
                            JOIN tbl_users u ON lp.farmer_id = u.id 
                            WHERE lp.availability = 'available' 
                            ORDER BY lp.date_posted DESC"; // Order by date_posted in descending order
    $livestock_posts_result = mysqli_query($con, $livestock_posts_query);

    // Error handling for livestock posts query
    if (!$livestock_posts_result) {
        die("Livestock posts query failed: " . mysqli_error($con));
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/buyer_browse.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'browse_livestock';
            include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
                <div class="search">
                    <i class="fas fa-filter" onclick="showFilterPopup()"></i>
                    <input type="text" placeholder="Search Livestock">
                </div>
            </header>

            <!-- Filter Popup -->
            <div id="filter-popup" class="filter-popup" style="display:none;">
                <div class="filter-content">
                    <span class="close" onclick="hideFilterPopup()">&times;</span>
                    <h2>Search Livestock For Sale</h2>
                    <div class="auction-options">
                        <button class="auction-btn active">Private Treaty</button>
                        <button class="auction-btn">Online Auctions</button>
                    </div>
                    <div class="animal-icons">
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Bulls">Bulls</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Cows">Cows</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Horses">Horses</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Pigs">Pigs</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Goats">Goats</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Sheep">Sheep</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Alpacas">Alpacas</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Rabbits">Rabbits</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Dogs">Dogs</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Cats">Cats</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Humans">Humans</div>
                    </div>
                    <div class="filter-search">
                        <select>
                            <option value="">Livestock</option>
                            <option value="cows">Chickens</option>
                            <option value="bulls">Bulls</option>
                            <option value="cows">Cows</option>
                            <option value="cows">Pigs</option>
                        </select>
                        <select>
                            <option value="">Breed</option>
                            <option value="roundhead">Roundhead</option>
                            <option value="hatch">Hatch</option>
                            <option value="sweater">Sweater</option>
                        </select>
                        <button onclick="applyFilters()">Search</button>
                    </div>
                </div>
            </div>

            <!-- Container for displaying posts -->
            <div class="listings">
                <?php
                // Check if livestock posts exist and display them
                if (mysqli_num_rows($livestock_posts_result) > 0) {
                    while ($row = mysqli_fetch_assoc($livestock_posts_result)) {
                        // Format the date and time as needed
                        $formatted_date_time = date('F j, Y, g:i A', strtotime($row['date_posted']));

                        // Set default image if no image is provided
                        $default_image = '../../Assets/default-profile.png'; // Use the specified default profile image
                        $image_url = !empty($row['image_posts']) && file_exists('../../uploads/livestock_posts/' . $row['image_posts']) 
                            ? '../../uploads/livestock_posts/' . htmlspecialchars($row['image_posts']) 
                            : $default_image; // Fallback to default profile image if no livestock image
                ?>
                        <div class="listing-card">
                            <div class="listing-image">
                                <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img">
                            </div>
                            <div class="listing-details">
                                <div class="listing-info">
                                    <div class="livestock-title"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div class="farmer-name"><?php echo htmlspecialchars($row['farmer_first_name'] . ' ' . $row['farmer_last_name']); ?></div>
                                    <div class="post-date"><?php echo $formatted_date_time; ?></div>
                                    <div class="description">
                                        <ul>
                                            <li><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></li>
                                            <li><strong>Quantity Available:</strong> <?php echo htmlspecialchars($row['quantity']); ?></li>
                                            <li><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="actions">
                                    <div class="likes">5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)</div>
                                    <button class="chat-button">CHAT</button>
                                    <button class="details-button">VIEW FULL DETAILS</button>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>No livestock posts available.</p>';
                }

                // Free the result set
                mysqli_free_result($livestock_posts_result);
                ?>
            </div>

        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../js/filtering.js"></script>
<script src="../../js/livestock/main.js"></script>
</html>
