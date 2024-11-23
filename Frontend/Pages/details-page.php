<?php
session_start();
include('../../Backend/db/db_connect.php');

$type_visit = isset($_GET['why_are_here']) ? $_GET['why_are_here'] : '';

// Check if user is logged in and retrieve user_type
if (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];
} else {
    echo "User not logged in.";
    exit();
}

// Fetch the post_id from the URL
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

$user_id = $_SESSION['id'];

// Query to get the specific livestock details
$listing_query = "SELECT lp.post_id, lp.title, lp.description, lp.price, lp.quantity, lp.image_posts, lp.date_posted, 
                  lp.livestock_type, lp.breed, lp.weight, lp.health_status, lp.location, u.id, u.first_name, u.last_name, u.profile_picture 
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

// Determine back URL based on user type

$back_to_me = "../../Frontend/Pages/users-profile-page.php";
if ($user_type === 'farmer') {
    $back_url = "../../Frontend/Farmer/browse_livestock.php";
} elseif ($user_type === 'buyer') {
    $back_url = "../../Frontend/Buyer/browse_livestock.php";
} else {
    $back_url = "../../Frontend/landingpage.php"; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/details-page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livestock | Details</title>
</head>

<body>

    <!-- Floating Back Icon -->
    <div class="floating-back-icon">
        <?php if ($type_visit == "profile") { ?>
            <a href="<?php echo $back_to_me . '?user_id=' . $user_id; ?>" aria-label="Back to Profile">
                <i class="fas fa-arrow-left"></i>
            </a>
        <?php } else { ?>
            <a href="<?php echo $back_url; ?>" aria-label="Go Back">
                <i class="fas fa-arrow-left"></i>
            </a>
        <?php } ?>
    </div>

    </div>
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
            <?php if($type_visit != "profile") { ?>
                <div class="chatbox-section">
                <h3>Chat with Seller</h3>
                <div class="chat-input-section">
                    <input type="text" class="chat-input" id="chat-input-field" placeholder="Type your message here...">
                    <button
                        class="send-button"
                        onclick="sendMessage(<?php echo $user_id ?>, <?php echo htmlspecialchars($listing['id']); ?>)">Send</button>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
    <script>
        document.getElementById("chat-input-field").value = "Hello World";

        function sendMessage(uid, id) {
            if (uid === id) {
                window.location.href = '../../Frontend/Farmer/message';
            } else {
                const message = document.getElementById("chat-input-field").value;

                if (!message.trim()) {
                    alert("Please type a message.");
                    return;
                }

                const data = {
                    sender: uid,
                    receiver: id,
                    initial: message
                };

                fetch('../../Backend/chat/start_chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        alert('Message sent successfully!');
                        document.getElementById("chat-input-field").value = '';
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        alert('Error sending message.');
                    });
            }
        }
    </script>
</body>

</html>