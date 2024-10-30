<?php
    session_start();
    include('../../Backend/db/db_connect.php'); 

    // Check if the user is logged in as a farmer
    if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
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

    // Check if profile picture exists and file exists on server
    if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
        $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
    } else {
        $profile_image = $default_profile_picture;
    }

    // Fetch livestock listings for the logged-in farmer, ordered by the latest added first
    $listings_query = "SELECT lp.*, u.first_name, u.last_name 
    FROM livestock_posts lp 
    JOIN tbl_users u ON lp.farmer_id = u.id 
    WHERE lp.farmer_id = ? 
    ORDER BY lp.date_posted DESC"; // Order by date_posted in descending order
    $listings_stmt = mysqli_prepare($con, $listings_query);
    mysqli_stmt_bind_param($listings_stmt, "i", $user_id);
    mysqli_stmt_execute($listings_stmt);
    $listings_result = mysqli_stmt_get_result($listings_stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/farmer_browse.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
    <div class="container">
        <?php 
            $page = 'browse_livestock';
            include('../../sidebar/sidebar-farmer.php');
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
                            <!-- Add more options as needed -->
                        </select>
                        <select>
                            <option value="">Breed</option>
                            <option value="cows">Roundhead</option>
                            <option value="cows">Hatch</option>
                            <option value="cows">Sweater</option>
                            <!-- Add class options here -->
                        </select>
                        <button onclick="applyFilters()">Search</button>
                    </div>
                </div>
            </div>

            <!-- Container for displaying posts -->
            <div class="listings">
            <?php
                // Check if listings exist and display them
                if ($listings_result->num_rows > 0) {
                    while ($listing = mysqli_fetch_assoc($listings_result)) {
                        // Format the date and time as needed
                        $formatted_date_time = date('F j, Y, g:i A', strtotime($listing['date_posted']));
                        
                        // Set default image if no image is provided
                        $default_image = '../../Assets/default-profile.png'; 
                        $image_url = !empty($listing['image_posts']) && file_exists('../../uploads/livestock_posts/' . $listing['image_posts']) 
                            ? '../../uploads/livestock_posts/' . htmlspecialchars($listing['image_posts']) 
                            : $default_image; // Fallback to default profile image if no livestock image

                        ?>
                        <div class="listing-card">
                            <div class="listing-image">
                                <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img">
                            </div>
                            <div class="listing-details">
                                <div class="listing-info">
                                    <div class="livestock-title"><?php echo htmlspecialchars($listing['title']); ?></div>
                                    <div class="farmer-name"><?php echo htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']); ?></div>
                                    <div class="post-date"><?php echo $formatted_date_time; ?></div>
                                    <div class="description">
                                        <ul>
                                            <li><strong>Animal Type:</strong> <?php echo htmlspecialchars($listing['livestock_type']); ?></li>
                                            <li><strong>Breed:</strong> <?php echo htmlspecialchars($listing['breed']); ?></li>
                                            <li><strong>Quantity Available:</strong> <?php echo htmlspecialchars($listing['quantity']); ?></li>
                                            <li><strong>Weight:</strong> <?php echo htmlspecialchars($listing['weight']); ?> kg</li>
                                            <li><strong>Health Status:</strong> <?php echo htmlspecialchars($listing['health_status']); ?></li>
                                            <li><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?></li>
                                            <li><strong>Price:</strong> $<?php echo htmlspecialchars($listing['price']); ?></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="actions">
                                    <div class="likes">5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)</div>
                                    <button class="chat-button">CHAT</button>
                                    <button class="details-button">VIEW FULL DETAILS</button>
                                    <button class="update-button" onclick="updateListing(<?php echo $listing['post_id']; ?>)">UPDATE</button>
                                    <button class="delete-button" onclick="deleteListing(<?php echo $listing['post_id']; ?>)">DELETE</button>
                                </div>

                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>No livestock listings available.</p>';
                }

                // Close the statement
                mysqli_stmt_close($listings_stmt);
                ?>
                </div>

        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../js/filtering.js"></script>
<script src="../../js/livestock/main.js"></script>
</html>
