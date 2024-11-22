<?php
session_start();
include('../../Backend/db/db_connect.php');

// Default profile picture
$default_profile_picture = '../../Assets/default-profile.png';

// Check if the connection is established
if (!isset($con) || $con->connect_error) {
    die("Database connection failed: " . ($con->connect_error ?? 'Unknown error'));
}

// Check if `user_id` is passed in the URL
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Ensure it's an integer to prevent SQL injection

    // Prepare and execute the query
    $query = "SELECT profile_picture, first_name, last_name, user_type FROM tbl_users WHERE id = ?";
    $stmt = $con->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user details
            $user = $result->fetch_assoc();
            $profile_image = !empty($user['profile_picture']) ? '../../uploads/profile_pictures/' . $user['profile_picture'] : $default_profile_picture;
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
            $user_type = $user['user_type'];
        } else {
            die("User not found.");
        }
    } else {
        die("Query preparation failed: " . $con->error);
    }
} else {
    die("User ID not provided.");
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Profile</title>
    <link rel="stylesheet" href="../../css/users-profile-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="profile-container">
        <!-- Cover Photo -->
        <div class="cover-photo">
            <img src="../../Assets/Cow-Gang.jpg" alt="Cover Photo">
        </div>

        <div class="content-wrapper">
            <!-- Profile Header -->
            <header class="profile-header">
                <div class="left-side">
                    <div class="profile-picture">
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture" class="profile-img">
                        <div class="camera-icon" onclick="toggleDropdown(event)">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div class="dropdown-content">
                            <form action="../../Backend/user-profile/update_picture.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="update_type" value="profile">
                                <input type="file" name="picture" accept="image/*" required>
                                <button type="submit">Update Profile Picture</button>
                            </form>
                            <form action="../../Backend/user-profile/update_picture.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="update_type" value="cover">
                                <input type="file" name="picture" accept="image/*" required>
                                <button type="submit">Update Cover Picture</button>
                            </form>
                        </div>

                    </div>


                    <div class="profile-info">
                        <h1><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h1>
                        <div class="info-and-rating">
                            <p class="role"><?php echo htmlspecialchars(ucfirst($user_type)); ?></p>
                            <div class="rating">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="right-side">
                    <!-- New section added on the right -->
                    <div class="profile-stats">
                        <p><strong>Profile Visits:</strong> 2,350</p>
                        <p><strong>Livestock Sold:</strong> 125</p>
                    </div>

                    <!-- Adjusted Message Button -->
                    <button class="message-btn">Message</button>
                </div>
            </header>



            <div class="main-content">
                <!-- Sidebar -->
                <div class="user-info">
                    <!-- Information Section -->
                    <h3>User Info</h3>
                    <div class="information">
                        <p><strong>Location:</strong> <span>Manila, Philippines</span></p>
                        <p><strong>Age:</strong> <span>30</span></p>
                        <p><strong>Bio:</strong> <span>Experienced farmer specializing in livestock management. Passionate about sustainable agriculture.</span></p>
                        <p><strong>Date Joined:</strong> <span>January 15, 2020</span></p>
                    </div>

                    <!-- Stats Section -->
                    <div class="stats">
                        <p>Performance: <strong>56</strong> with <strong>42 reviews</strong></p>
                    </div>

                    <!-- Feedback Section -->
                    <div class="feedbacks">
                        <h3>Buyer Feedback</h3>
                        <!-- Individual Feedback Card -->
                        <div class="feedback-card">
                            <div class="rating">
                                ★★★★☆
                            </div>
                            <p><strong>John Doe:</strong> "Great experience, the livestock was healthy and exactly as described. Highly recommend!"</p>
                        </div>

                        <div class="feedback-card">
                            <div class="rating">
                                ★★★★★
                            </div>
                            <p><strong>Jane Smith:</strong> "Reliable farmer, smooth transaction. I will definitely buy again!"</p>
                        </div>

                        <!-- "More" Button -->
                        <button class="more-btn">More Feedback</button>

                        <div class="add-feedback">
                            <textarea placeholder="Leave your feedback here..." rows="4" cols="30"></textarea>
                            <button class="submit-feedback">Submit Feedback</button>
                        </div>
                    </div>
                </div>



                <!-- Feed Section: Livestock Post, Forum, Feedback, and Ratings -->
                <div class="feed">
                    <nav class="feed-nav">
                        <a href="#" class="active">Livestock Post</a>
                        <a href="#">Livestock Forum</a>
                        <a href="#">Feedback & Ratings</a>
                    </nav>

                    <!-- Livestock Post Section -->
                    <div class="listings">
                        <div class="listing-card">
                            <div class="card-header">
                                <div class="rates">⭐ 5.0 (1.1k)</div>
                                <div class="availability"> Available now </div>
                                <div class="bookmark"> <i class="far fa-heart bookmark-icon"></i> </div>

                                <!-- Only show for the user who posted the listing -->
                                <div class="kebab-menu">
                                    <button class="kebab-button" onclick="toggleDropdown()">
                                        <span class="kebab-icon"><i class="fas fa-ellipsis-v"></i></span>
                                    </button>
                                    <div id="dropdown" class="dropdown-content">
                                        <button class="update-button" onclick="openUpdateModal()">UPDATE</button>
                                        <button class="delete-button" onclick="deleteListing()">DELETE</button>
                                    </div>
                                </div>

                            </div>

                            <div class="post-date">October 1, 2024, 2:30 PM</div>

                            <div class="listing-image">
                                <img src="../../Assets/Livestock.jpg" alt="Livestock Image" class="livestock-img">
                            </div>

                            <div class="card-details">
                                <div class="card-info" id="declaration-of-chat">
                                    <div class="animal-type">Cow</div>

                                    <div class="card-bottom-info">
                                        <div class="livestock-title"> <strong>Livestock Title</strong></div>
                                        <div class="price"> <strong>₱10,000</strong> /Head</div>
                                    </div>
                                </div>

                                <div class="card-more-info">
                                    <div class="info-item">
                                        <i class="fas fa-box"></i> <!-- Icon for Quantity -->
                                        10
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-paw"></i> <!-- Icon for Breed -->
                                        Holstein
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-weight-hanging"></i> <!-- Icon for Weight -->
                                        500 kg
                                    </div>
                                    <div class="view-button">
                                        <a href="../../Frontend/Pages/details-page.php" class="view-button-link">
                                            <button type="button">VIEW</button>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="listing-card">
                            <div class="card-header">
                                <div class="rates">⭐ 5.0 (1.1k)</div>
                                <div class="availability"> Available now </div>
                                <div class="bookmark"> <i class="far fa-heart bookmark-icon"></i> </div>

                                <!-- Only show for the user who posted the listing -->
                                <div class="kebab-menu">
                                    <button class="kebab-button" onclick="toggleDropdown()">
                                        <span class="kebab-icon"><i class="fas fa-ellipsis-v"></i></span>
                                    </button>
                                    <div id="dropdown" class="dropdown-content">
                                        <button class="update-button" onclick="openUpdateModal()">UPDATE</button>
                                        <button class="delete-button" onclick="deleteListing()">DELETE</button>
                                    </div>
                                </div>

                            </div>

                            <div class="post-date">October 1, 2024, 2:30 PM</div>

                            <div class="listing-image">
                                <img src="../../Assets/Goat-Gang.jpg" alt="Livestock Image" class="livestock-img">
                            </div>

                            <div class="card-details">
                                <div class="card-info" id="declaration-of-chat">
                                    <div class="animal-type">Cow</div>

                                    <div class="card-bottom-info">
                                        <div class="livestock-title"> <strong>Livestock Title</strong></div>
                                        <div class="price"> <strong>₱10,000</strong> /Head</div>
                                    </div>
                                </div>

                                <div class="card-more-info">
                                    <div class="info-item">
                                        <i class="fas fa-box"></i> <!-- Icon for Quantity -->
                                        10
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-paw"></i> <!-- Icon for Breed -->
                                        Holstein
                                    </div>
                                    <div class="info-item">
                                        <i class="fas fa-weight-hanging"></i> <!-- Icon for Weight -->
                                        500 kg
                                    </div>
                                    <div class="view-button">
                                        <a href="../../Frontend/Pages/details-page.php" class="view-button-link">
                                            <button type="button">VIEW</button>
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                </div>
            </div>



        </div>
    </div>
    </div>
</body>

<script>
    // Function to toggle dropdown visibility
    function toggleDropdown(event) {
        const dropdown = event.currentTarget.nextElementSibling; // Get the next sibling (the dropdown)
        dropdown.classList.toggle('show');
    }

    // Close the dropdown if clicked outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.camera-icon') && !event.target.matches('.dropdown-content') && !event.target.closest('.profile-picture')) {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('show');
            });
        }
    };
</script>

</html>