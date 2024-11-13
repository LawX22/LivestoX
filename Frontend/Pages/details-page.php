<?php
session_start();
include('../../Backend/db/db_connect.php');

// Fetch the post_id from the URL
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Query to get the specific livestock details
$listing_query = "SELECT lp.post_id, lp.title, lp.description, lp.price, lp.quantity, lp.image_posts, lp.date_posted, 
                  lp.livestock_type, lp.breed, lp.weight, lp.health_status, lp.location, u.first_name, u.last_name 
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
        <!-- Left Side: Empty Space -->
        <div class="left-side">
        <img src="<?php echo $image_url; ?>" alt="Livestock Image" class="livestock-img-large">

        </div>

        <!-- Right Side: All Content -->
        <div class="right-side">
           
            <h2><?php echo htmlspecialchars($listing['title']); ?></h2>

            <div class="price-quantity">
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($listing['price']); ?> / Head</p>
                <p><strong>Quantity Available:</strong> <?php echo htmlspecialchars($listing['quantity']); ?></p>
            </div>

            <div class="livestock-details">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($listing['livestock_type']); ?></p>
                <p><strong>Breed:</strong> <?php echo htmlspecialchars($listing['breed']); ?></p>
                <p><strong>Weight:</strong> <?php echo htmlspecialchars($listing['weight']) . ' kg'; ?></p>
                <p><strong>Health Status:</strong> <?php echo htmlspecialchars($listing['health_status']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?></p>
                <p><strong>Date Posted:</strong> <?php echo $formatted_date_time; ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($listing['description']); ?></p>
            </div>

            <h3>Farmer Information</h3>
            <div class="farmer-details">
                <p><strong>Farmer:</strong> <?php echo htmlspecialchars($listing['first_name'] . ' ' . $listing['last_name']); ?></p>
                <button class="contact-button">Start Chat</button>
            </div>
        </div>
    </div>
</body>
</html>
