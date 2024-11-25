<?php
session_start();
include('../../Backend/db/db_connect.php');

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../../Frontend/login.php");
    exit();
}

$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name, profile_picture, user_type FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name, $profile_picture, $user_type);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$default_profile_picture = '../../Assets/default-profile.png';
$profile_image = !empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)
    ? '../../uploads/profile_pictures/' . $profile_picture
    : $default_profile_picture;

$livestock_posts_query = "SELECT lp.post_id, lp.title, lp.description, lp.price, lp.quantity, lp.image_posts, lp.date_posted, lp.livestock_type, lp.breed, lp.weight, u.id, u.first_name AS farmer_first_name, u.last_name AS farmer_last_name 
                        FROM livestock_posts lp 
                        JOIN tbl_users u ON lp.farmer_id = u.id 
                        WHERE lp.availability = 'available' 
                        ORDER BY lp.date_posted DESC";
$livestock_posts_result = mysqli_query($con, $livestock_posts_query);

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
    <link rel="stylesheet" href="../../css/admin_browse.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <script type="module" src="../../js/vue/start-chat.js" async></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php
        $page = 'browse_livestock';
        include('../../sidebar/sidebar-admin.php');
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
                    </div>
                    <div class="filter-search">
                        <select>
                            <option value="">Livestock</option>
                            <option value="cows">Chickens</option>
                            <option value="bulls">Bulls</option>
                            <option value="cows">Cows</option>
                        </select>
                        <select>
                            <option value="">Breed</option>
                            <option value="roundhead">Roundhead</option>
                            <option value="hatch">Hatch</option>
                        </select>
                        <button onclick="applyFilters()">Search</button>
                    </div>
                </div>
            </div>

            <div class="listings">
                <?php
                if (mysqli_num_rows($livestock_posts_result) > 0) {
                    while ($row = mysqli_fetch_assoc($livestock_posts_result)) {
                        $formatted_date_time = date('F j, Y, g:i A', strtotime($row['date_posted']));
                        $default_image = '../../Assets/default-profile.png';
                        $image_url = !empty($row['image_posts']) && file_exists('../../uploads/livestock_posts/' . $row['image_posts'])
                            ? '../../uploads/livestock_posts/' . htmlspecialchars($row['image_posts'])
                            : $default_image;
                ?>
                        <div class="listing-card" id="declaration-of-chat">
                            <div class="card-header">
                                <div class="rates">⭐ 5.0 (1.1k)</div>
                                <div class="availability"> Available now </div>
                                <div class="bookmark"> <i class="far fa-heart bookmark-icon"></i> </div>
                            </div>

                            <div class="post-date"><?php echo $formatted_date_time; ?></div>

                            <div class="listing-image">
                                <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img">
                            </div>

                            <div class="card-details">
                                <div class="card-info">
                                    <div class="animal-type"><?php echo htmlspecialchars($row['livestock_type'] ?? 'Unknown Type'); ?></div>

                                    <div class="card-bottom-info">
                                        <div class="livestock-title"> <strong><?php echo htmlspecialchars($row['title']); ?></strong></div>
                                        <div class="price"> <strong>$<?php echo htmlspecialchars($row['price']); ?></strong> /Head</div>
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

                                <!-- <input type="text" v-model="firstTime">
                                <button
                                    @click="startWarBuyer(<?php echo $user_id ?>, <?php echo $row['id']; ?>)"
                                    class="chat-button">CHAT
                                </button> -->

                                <div class="actions">
                                    <button class="update-button" onclick="openUpdateModal(<?php echo $row['post_id']; ?>)">UPDATE</button>
                                    <button class="delete-button" onclick="deleteListing(<?php echo $row['post_id']; ?>)">DELETE</button>
                                </div>

                            </div>

                        </div>
                <?php
                    }
                } else {
                    echo '<p>No livestock posts available.</p>';
                }
                mysqli_free_result($livestock_posts_result);
                ?>
            </div>
        </div>
    </div>
</body>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/filtering.js"></script>
<script src="../../js/livestock/main.js"></script>

</html>