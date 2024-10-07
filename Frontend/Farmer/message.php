<?php
session_start();
include('../../Backend/db/db_connect.php'); 

// Redirect if the user is not logged in or is not a buyer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'farmer') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch the logged-in user's full details including profile picture and user type
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

// Check if the profile picture exists and file exists on server
if (!empty($profile_picture) && file_exists('../../uploads/profile_pictures/' . $profile_picture)) {
    $profile_image = '../../uploads/profile_pictures/' . $profile_picture;
} else {
    $profile_image = $default_profile_picture;
}

// Fetch all buyer users (if needed for display)
$sql1 = "SELECT * FROM tbl_users WHERE user_type = 'farmer'";
$result1 = mysqli_query($con, $sql1);

// Fetch posts with associated user information
$postQuery = "SELECT f.id, f.title, f.description, f.image, f.created_at, f.user_id, u.first_name, u.last_name, u.user_type 
              FROM forum f 
              JOIN tbl_users u ON f.user_id = u.id 
              ORDER BY f.created_at DESC";
$postResult = mysqli_query($con, $postQuery);
$posts = mysqli_fetch_all($postResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Message   Page</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'inbox';
            include('../../sidebar/sidebar-farmer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="logo">LivestoX Logo Here</div>
            </header>
            <div class="chat-container">
                <div class="chat-list">
                    <div class="chat-list-header">
                        <h3>Livestock Users</h3>
                    </div>
                    <ul>
                        <li><div class="profile-circle">B</div>Buyer 7 </li>
                        <li><div class="profile-circle">F</div>Farmer 6 </li>
                        <li><div class="profile-circle">F</div>Farmer 5 </li>
                        <li><div class="profile-circle">B</div>Buyer 4 </li>
                        <li><div class="profile-circle">B</div>Buyer 3 </li>
                        <li><div class="profile-circle">F</div>Farmer 2 </li>
                        <li><div class="profile-circle">B</div>Buyer 1 </li>
                    </ul>
                </div>
                <div class="chat-window">
                <div class="chat-header">
                        <div class="profile-info">
                            <div class="main-profile-circle">F</div>
                            <h3>Farmer 7 FullName</h3>
                        </div>
                        <div class="calendar-icon">
                            <i class="fas fa-calendar"></i>
                        </div>
                    </div>
                    <div class="chat-message">
                        <p>Chatting Interface Here</p>
                    </div>
                    <div class="chat-footer">
                        <input type="text" placeholder="Type something...">
                    </div>
                </div>
                <div class="online-users">
                    <div class="chat-list-header">
                        <h3>Online - 2</h3>
                    </div>
                    <ul>
                        <li><div class="profile-circle">B</div>Buyer 8 </li>
                        <li><div class="profile-circle">B</div>Buyer 7 </li>
                        <li><div class="profile-circle">F</div>Farmer 6 </li>
                        <li><div class="profile-circle">F</div>Farmer 5 </li>
                        <li><div class="profile-circle">B</div>Buyer 4 </li>
                        <li><div class="profile-circle">F</div>Farmer 3 </li>
                        <li><div class="profile-circle">F</div>Farmer 2 </li>
                        <li><div class="profile-circle">B</div>Buyer 1 </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="../../js/logout-confirmation.js"></script>
</html>
