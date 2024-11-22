<?php
session_start();
include('../../Backend/db/db_connect.php');
include('../../Backend/db/function.php');


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

$livestock_posts_query = "SELECT * FROM favorite JOIN livestock_posts as lp ON favorite.stock_id = lp.post_id 
                            LEFT JOIN tbl_users as u ON u.id = lp.farmer_id WHERE user_id = $user_id ";
$livestock_posts_result = mysqli_query($con, $livestock_posts_query);



// Check if profile picture exists and file exists on server
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Buyer Dashboard</title>
    <link rel="stylesheet" href="../../css/buyer_dashboard.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <?php
        $page = 'buyer_dashboard';
        include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
            </header>

            <div class="dashboard-content">
                <!-- Overview Section -->
                <div class="dashboard-section">
                    <h2>Overview</h2>
                    <div class="overview-cards">
                        <div class="card">
                            <i class="fas fa-bell"></i>
                            <div>
                                <h3>4</h3>
                                <p>Notification</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-shopping-cart"></i>
                            <div>
                                <h3>5</h3>
                                <p>Purchase History</p>
                            </div>
                        </div>
                        <div class="card">
                            <i class="fas fa-heart"></i>
                            <div>
                                <h3>8</h3>
                                <p>Saved Listings</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="favorite_box">
                    <div class="title" style="display: block;">
                        <h1>FAVORITE</h1>
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
                                        <div class="bookmark">
                                            <form action="../../Backend/livestock_posts/add_fav.php" method="POST">
                                                <input type="hidden" name="stock_id" value="<?php echo $row['post_id']; ?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">

                                                <button type="submit" name="add_fav" style="border:none; background:none">
                                                    <i class="far fa-heart bookmark-icon"></i>
                                                </button>
                                            </form>
                                        </div>
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
        </div>
    </div>
</body>

<script src="../../js/logout-confirmation.js"></script>

</html>