<?php
session_start();
include('../../Backend/db/db_connect.php'); 

if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../../Frontend/login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['id'];
$query = "SELECT first_name, last_name FROM tbl_users WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_name, $last_name);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Inbox Page</title>
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/openforum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'openforum';
            include('../../sidebar/sidebar-admin.php');
        ?>
        <div class="main-content">
            <header>
                <div class="logo">LivestoX Logo Here</div>
                <div class="search">
                    <input type="text" placeholder="Search Livestock">
                </div>
            </header>
            <div class="open-forum">
                <h2>Q&A - Open Forum for Livestock Farmers and Buyers</h2>
                <button class="ask-question-btn">Ask a Question</button>
                <div class="forum-post card">
                    <div class="post-header">
                        <div class="profile-info">
                            <div class="profile-circle">F</div>
                            <div class="name">Farmer 10 FullName</div>
                            <div class="date">July 19, 2024</div>
                        </div>
                    </div>
                    <div class="post-content">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                    </div>
                    <div class="post-actions">
                        <div class="likes">
                            <i class="fas fa-thumbs-up"></i> 11k
                        </div>
                        <div class="dislikes">
                            <i class="fas fa-thumbs-down"></i> 500
                        </div>
                        <div class="comments">
                            <i class="fas fa-comments"></i> 100
                        </div>
                    </div>
                </div>
                <div class="forum-post card">
                    <div class="post-header">
                        <div class="profile-info">
                            <div class="profile-circle">B</div>
                            <div class="name">Buyer 3 FullName</div>
                            <div class="date">July 20, 2024</div>
                        </div>
                    </div>
                    <div class="post-content">
                        <!-- Post content goes here -->
                    </div>
                    <div class="post-actions">
                        <div class="likes">
                            <i class="fas fa-thumbs-up"></i> 5k
                        </div>
                        <div class="dislikes">
                            <i class="fas fa-thumbs-down"></i> 200
                        </div>
                        <div class="comments">
                            <i class="fas fa-comments"></i> 50
                        </div>
                    </div>
                </div>
                <div class="forum-post card">
                    <div class="post-header">
                        <div class="profile-info">
                            <div class="profile-circle">F</div>
                            <div class="name">Farmer 5 FullName</div>
                            <div class="date">July 21, 2024</div>
                        </div>
                    </div>
                    <div class="post-content">
                        <!-- Post content goes here -->
                    </div>
                    <div class="post-actions">
                        <div class="likes">
                            <i class="fas fa-thumbs-up"></i> 3k
                        </div>
                        <div class="dislikes">
                            <i class="fas fa-thumbs-down"></i> 100
                        </div>
                        <div class="comments">
                            <i class="fas fa-comments"></i> 30
                        </div>
                    </div>
                </div>
                <div class="forum-post card">
                    <div class="post-header">
                        <div class="profile-info">
                            <div class="profile-circle">F</div>
                            <div class="name">Farmer 22 FullName</div>
                            <div class="date">July 22, 2024</div>
                        </div>
                    </div>
                    <div class="post-content">
                        <!-- Post content goes here -->
                    </div>
                    <div class="post-actions">
                        <div class="likes">
                            <i class="fas fa-thumbs-up"></i> 1k
                        </div>
                        <div class="dislikes">
                            <i class="fas fa-thumbs-down"></i> 50
                        </div>
                        <div class="comments">
                            <i class="fas fa-comments"></i> 10
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
