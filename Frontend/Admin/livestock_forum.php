<?php
session_start();
include('../../Backend/db/db_connect.php');

// Redirect if the user is not logged in or is not a buyer
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
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
$sql1 = "SELECT * FROM tbl_users WHERE user_type = 'admin'";
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
    <title>LivestoX - Livestock Forum Page</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/livestock_forum.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <?php
        $page = 'livestock_forum';
        include('../../sidebar/sidebar-admin.php');
        ?>
        <div class="main-content">
            <header>
                <div class="livestock-logo">
                    <img src="../../Assets/livestock-logo.png" alt="Livestock Logo" class="livestock-img">
                    <div class="logo-name">LivestoX</div>
                </div>
            </header>
            <div class="open-forum">
                <h2>Q&A - Livestock Forum for Farmers and Buyers</h2>
                <!-- Ask a Question Button -->
                <button class="ask-question-btn">Ask a Question</button>

                <!-- Modal Popup -->
                <div id="questionModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Ask a Question</h2>
                        <form id="questionForm" action="#" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="questionTitle">Title:</label>
                                <input type="text" id="questionTitle" name="questionTitle" required>
                            </div>
                            <div class="form-group">
                                <label for="questionDescription">Description:</label>
                                <textarea id="questionDescription" name="questionDescription" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="questionImage">Upload Image (optional):</label>
                                <input type="file" id="questionImage" name="questionImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">Submit</button>
                        </form>
                    </div>
                </div>

                <!-- Modal Popup for Edit Post -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn">&times;</span>
                        <h2>Edit Post</h2>
                        <form id="editForm" action="../../Backend/forum/update_question.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="editPostId" name="postId">
                            <div class="form-group">
                                <label for="editTitle">Title:</label>
                                <input type="text" id="editTitle" name="editTitle" required>
                            </div>
                            <div class="form-group">
                                <label for="editDescription">Description:</label>
                                <textarea id="editDescription" name="editDescription" rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editImage">Upload Image (optional):</label>
                                <input type="file" id="editImage" name="editImage" accept="image/*">
                            </div>
                            <button type="submit" class="submit-btn">Save Changes</button>
                        </form>
                    </div>
                </div>

                <!-- Container for displaying posts -->
                <div id="postContainer" class="post-container">
                    <!-- Existing posts displayed here -->
                    <?php foreach ($posts as $post): ?>
                        <div class="forum-post card">
                            <div class="post-header">
                                <div class="profile-info">
                                    <div class="profile-image">
                                        <?php
                                        // Fetch the profile image of the post's author
                                        $post_user_id = $post['user_id'];
                                        $profileQuery = "SELECT profile_picture FROM tbl_users WHERE id = ?";
                                        $profileStmt = mysqli_prepare($con, $profileQuery);
                                        mysqli_stmt_bind_param($profileStmt, 'i', $post_user_id);
                                        mysqli_stmt_execute($profileStmt);
                                        mysqli_stmt_bind_result($profileStmt, $post_profile_picture);
                                        mysqli_stmt_fetch($profileStmt);
                                        mysqli_stmt_close($profileStmt);

                                        // Determine the correct profile picture to show
                                        if (!empty($post_profile_picture) && file_exists('../../uploads/profile_pictures/' . $post_profile_picture)) {
                                            $post_profile_image = '../../uploads/profile_pictures/' . $post_profile_picture;
                                        } else {
                                            $post_profile_image = $default_profile_picture; // Use default if not found
                                        }
                                        ?>
                                        <img src="<?php echo htmlspecialchars($post_profile_image); ?>" alt="Profile Image">
                                    </div>
                                    <div class="name"><?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']); ?></div>

                                    <!-- Display user type with dynamic background color -->
                                    <div class="user-type" style="background-color: <?= ($post['user_type'] === 'farmer') ? '#FFA908' : '#52B788'; ?>;">
                                        <?= htmlspecialchars($post['user_type']); ?>
                                    </div>

                                    <!-- Display date and time -->
                                    <div class="date-time-container">
                                        <div class="date">
                                            <?= date('F j, Y g:i:a', strtotime($post['created_at'])); ?>
                                        </div>

                                        <!-- Meatball menu -->
                                        <div class="meatball-menu">
                                            <i class="fas fa-ellipsis-v"></i>
                                            <div class="dropdown-menu">
                                                <!-- <a href="#" class="dropdown-item" data-post-id="<?= $post['id']; ?>">Edit</a> -->
                                                <a href="#" class="dropdown-item delete-post" data-post-id-delete="<?= $post['id']; ?>">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="post-content">
                                <h3><?= htmlspecialchars($post['title']); ?></h3>
                                <p><?= nl2br(htmlspecialchars($post['description'])); ?></p>

                                <!-- Slot for image post -->
                                <?php if ($post['image']): ?>
                                    <div class="post-image">
                                        <img src="<?= htmlspecialchars('../../uploads/forum_posts/' . $post['image']); ?>" alt="Post Image">
                                    </div>
                                <?php endif; ?>
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
                    <?php endforeach; ?>
                </div>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="../../js/logout-confirmation.js"></script>
                <script src="../../js/forum/forum-modal.js"></script>
                <script src="../../js/forum/submit-ajax.js"></script>
                <script src="../../js/forum/update-ajax.js"></script>
                <script src="../../js/forum/delete-ajax.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
                <script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
</body>

</html>