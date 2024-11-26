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
$listings_query = "SELECT lp.*, u.id, u.first_name, u.last_name 
    FROM livestock_posts lp 
    JOIN tbl_users u ON lp.farmer_id = u.id 
    ORDER BY lp.date_posted DESC "; // LIMIT 1 // Order by date_posted in descending order
$listings_stmt = mysqli_prepare($con, $listings_query);
mysqli_stmt_execute($listings_stmt);
$listings_result = mysqli_stmt_get_result($listings_stmt);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX | Browse Livestock </title>
    <script type="module" src="../../js/vue/start-chat.js" async></script>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/farmer/farmer_browse.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/modal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php
        $page = 'browse_livestock';
        include('../../sidebar/sidebar-farmer.php');
        ?>
        <div class="main-content">
            <header>
                

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
                    <!-- Add your filtering content here -->
                </div>
            </div>

            <!-- Container for displaying posts -->
            <div class="listings">
                <?php
                if (mysqli_num_rows($listings_result) > 0) {
                    while ($row = mysqli_fetch_assoc($listings_result)) {
                        $formatted_date_time = date('F j, Y, g:i A', strtotime($row['date_posted']));
                        $default_image = '../../Assets/default-profile.png';
                        $image_url = !empty($row['image_posts']) && file_exists('../../uploads/livestock_posts/' . $row['image_posts'])
                            ? '../../uploads/livestock_posts/' . htmlspecialchars($row['image_posts'])
                            : $default_image;
                ?>
                        <div class="listing-card">
                            <div class="card-header">
                                <div class="rates">⭐ 5.0 (1.1k)</div>
                                <div class="availability"> Available now </div>
                                <div class="bookmark"> <i class="far fa-heart bookmark-icon"></i> </div>

                                <?php
                                // Inside your listings loop
                                if ($user_id == $row['farmer_id']) { // Only show for the farmer who posted the listing
                                ?>
                                    <div class="kebab-menu">
                                        <button class="kebab-button" onclick="toggleDropdown(<?php echo $row['post_id']; ?>)">
                                            <span class="kebab-icon"><i class="fas fa-ellipsis-v"></i></span>
                                        </button>
                                        <div id="dropdown-<?php echo $row['post_id']; ?>" class="dropdown-content">
                                            <button class="update-button" onclick="openUpdateModal(<?php echo $row['post_id']; ?>)">UPDATE</button>
                                            <!-- Inside your PHP loop -->
                                            <button class="delete-button" onclick="deleteListing(<?php echo $row['post_id']; ?>)">DELETE</button>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                            </div>

                            <div class="post-date"><?php echo $formatted_date_time; ?></div>

                            <div class="listing-image">
                                <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img">
                            </div>

                            <div class="card-details">
                                <div class="card-info" id="declaration-of-chat">
                                    <div class="animal-type"><?php echo htmlspecialchars($row['livestock_type'] ?? 'Unknown Type'); ?></div>

                                    <div class="card-bottom-info">
                                        <div class="livestock-title"> <strong><?php echo htmlspecialchars($row['title']); ?></strong></div>
                                        <div class="price"> <strong>₱<?php echo htmlspecialchars($row['price']); ?></strong> /Head</div>
                                    </div>
                                </div>

                                <div class="card-more-info">
                                    <div class="info-item">
                                        <i class="fas fa-box"></i> <!-- Icon for Quantity -->
                                        <?php echo htmlspecialchars($row['quantity']); ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-paw"></i> <!-- Icon for Breed -->
                                        <?php echo htmlspecialchars($row['breed'] ?? 'Unknown Breed'); ?>
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-weight-hanging"></i> <!-- Icon for Weight -->
                                        <?php echo htmlspecialchars($row['weight'] ?? 'Unknown Weight') . ' kg'; ?>
                                    </div>
                                    <div class="view-button">
                                        <a href="../../Frontend/Pages/details-page.php?post_id=<?php echo $row['post_id']; ?>" class="view-button-link">
                                            <button type="button">VIEW</button>
                                        </a>
                                    </div>

                                </div>

                            </div>

                        </div>
                <?php
                    }
                } else {
                    echo '<p>No livestock posts available.</p>';
                }
                mysqli_free_result($listings_result); // Free result set
                ?>
            </div>
        </div>
    </div>
    <?php include('../../footer/footer.php'); ?>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/filtering.js"></script>
<script src="../../js/livestock/main.js"></script>
<script src="../../js/livestock/open_modal.js"></script>
<script src="../../js/livestock/add.js"></script>
<script src="../../js/livestock/update.js"></script>
<script src="../../js/livestock/delete.js"></script>

</html>