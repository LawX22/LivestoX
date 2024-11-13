<?php
session_start();
include('../../Backend/db/db_connect.php');

// Fetch the post_id from the URL
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Query to get the specific livestock details
$listing_query = "SELECT lp.post_id, lp.title, lp.description, lp.price, lp.quantity, lp.image_posts, lp.date_posted, 
                  lp.livestock_type, lp.breed, lp.weight, lp.health_status, lp.location, u.first_name, u.last_name, u.profile_picture 
                  FROM livestock_posts lp 
                  JOIN tbl_users u ON lp.farmer_id = u.id 
                  WHERE lp.post_id = ?";
$stmt = mysqli_prepare($con, $listing_query);
mysqli_stmt_bind_param($stmt, 'i', $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$listing = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Check if the listing exists
if (!$listing) {
    echo "Listing not found.";
    exit();
}

// Format date
$formatted_date_time = date('F j, Y, g:i A', strtotime($listing['date_posted']));

// Set default image if none exists
$default_image = '../../Assets/default-profile.png';
$image_url = !empty($listing['image_posts']) && file_exists('../../uploads/livestock_posts/' . $listing['image_posts'])
    ? '../../uploads/livestock_posts/' . htmlspecialchars($listing['image_posts'])
    : $default_image;

// Set default profile picture for farmer if none exists
$farmer_profile_picture = !empty($listing['profile_picture']) && file_exists('../../uploads/profile_pictures/' . $listing['profile_picture'])
    ? '../../uploads/profile_pictures/' . htmlspecialchars($listing['profile_picture'])
    : $default_image;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/details-page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock Details</title>
</head>

<body>
    <div class="container">
        <!-- Left Side: Image -->
        <div class="left-side">
            <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img-large">
        </div>

        <!-- Right Side: Details and Seller Info -->
        <div class="right-side">
            <h2><?php echo htmlspecialchars($listing['title']); ?></h2>
            <p class="price">PHP <?php echo htmlspecialchars($listing['price']); ?></p>
            <p class="listing-time">Listed on <?php echo $formatted_date_time; ?></p>

            <!-- About the Livestock -->
            <div class="details-section">
                <h3>About this Livestock</h3>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($listing['livestock_type']); ?></p>
                <p><strong>Breed:</strong> <?php echo htmlspecialchars($listing['breed']); ?></p>
                <p><strong>Weight:</strong> <?php echo htmlspecialchars($listing['weight']) . ' kg'; ?></p>
                <p><strong>Health Status:</strong> <?php echo htmlspecialchars($listing['health_status']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($listing['description']); ?></p>
            </div>

            <!-- Seller Information -->
            <div class="seller-section">
                <h3>Seller's Information</h3>
                <div class="farmer-details">
                    <img src="<?php echo $farmer_profile_picture; ?>" alt="Farmer Profile" class="farmer-profile-img">
                    <p><strong><?php echo htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']); ?></strong></p>
                </div>
            </div>

            <!-- Chatbox Section -->
            <div class="chatbox-section">
                <h3>Chat with Seller</h3>
                <div class="chat-input-section">
                    <input type="text" class="chat-input" placeholder="Type your message here...">
                    <button class="send-button">Send</button>
                </div>
            </div>

        </div>
    </div>

</body>

</html>