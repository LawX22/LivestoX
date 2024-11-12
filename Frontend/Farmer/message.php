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
    <title>LivestoX - Message Page</title>
    <script type="module" src="../../js/vue/farmer-messages.js" async></script>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
    .iframe-container {
      width: 100%;
      max-height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      border: 2px solid red;
      border-radius: 8px;
    }

    iframe {
      width: 100%;
      max-height: 100%;
      border: none;
    }
  </style>
</head>
<body>
    <div id="farmer" class="container">
        <?php 
            $page = 'message';
            include('../../sidebar/sidebar-farmer.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
            </header>
            <div class="chat-container">
                <!-- Left section with users and search bar -->
                <div class="chat-list">
                    <div class="chat-list-header">
                        <h3>Livestock Users</h3>
                    </div>
                    <!-- Search bar for users -->
                    <div class="search-bar">
                            <input type="text" placeholder="Search for users...">
                            <button class="search-button"><i class="fas fa-search"></i></button>
                        </div>
                    <div class="chats">
                    <ul>
                    <?php include('../../Backend/chat/get_chat.php'); ?>
                        <?php foreach ($chats as $row): ?>
                        <a href="../Pages/direct_message?c=<?= $row['gochat_id'] ?>&m=<?php echo $user_id ?>&s=<?= $row['status'] ?>&p=<?= $row['profile_picture'] ?>&n=<?= $row['full_name'] ?>" target="main-content">
                            <li>
                                <img src="../../uploads/profile_pictures/<?= $row['profile_picture'] ?>" alt="?" class="profile-circle">
                                <div class="chat-preview">
                                    <span class="user-name"><?= $row['full_name'] ?></span>
                                    <span class="last-message">Last message here...</span>
                                </div>
                            </li>
                        </a>
                        <?php endforeach; ?>
                        <!-- Repeat for other users -->
                    </ul>
                    </div>
                </div>
                
                <!-- Chat window -->
                <div class="iframe-container">
                <iframe :src="conversations.length > 0 ? '../Pages/direct_message?c=' + conversations[0].conversation_id : ''" 
                        name="main-content" 
                        frameborder="0">
                </iframe>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="../../js/logout-confirmation.js"></script>
</html>
