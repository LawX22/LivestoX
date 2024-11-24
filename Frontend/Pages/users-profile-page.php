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
    $query = "SELECT profile_picture, cover_photo, phone, date_joined, location, bio, first_name, last_name, user_type FROM tbl_users WHERE id = ?";
    $stmt = $con->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch user details
            $user = $result->fetch_assoc();
            $profile_image = !empty($user['profile_picture']) ? '../../uploads/profile_pictures/' . $user['profile_picture'] : $default_profile_picture;
            $cover_photo = !empty($user['cover_photo']) ? '../../uploads/cover_photos/' . $user['cover_photo'] : $default_cover_photo;
            $first_name = $user['first_name'];
            $last_name = $user['last_name'];
            $user_type = $user['user_type'];
            $phone = $user['phone'];
            $date_joined = $user['date_joined'];
            $location = $user['location'];
            $bio = $user['bio'];
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

<style>
    .feed-nav {
        display: flex;
        gap: 1rem;
        border-bottom: 2px solid #ccc;
    }

    .feed-nav .tab-link {
        text-decoration: none;
        padding: 0.5rem 1rem;
        color: #333;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .feed-nav .tab-link.active {
        font-weight: bold;
        border-bottom: 2px solid #007bff;
    }

    .tab-content {
        margin-top: 1rem;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }



    .edit-info-modal,
    .edit-bio-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .edit-info-modal-content,
    .edit-bio-modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        text-align: center;
    }

    .close-edit-info-modal,
    .close-edit-bio-modal {
        float: right;
        font-size: 20px;
        cursor: pointer;
    }

</style>

<body>
    <div class="profile-container">
        <!-- Cover Photo -->
        <div class="cover-photo">
            <img src="<?php echo htmlspecialchars($cover_photo); ?>">
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
                        <form id="updateProfilePictureForm" enctype="multipart/form-data">
                            <input type="hidden" name="update_type" value="profile">
                            <input type="file" name="picture" accept="image/*" required>
                            <button type="submit">Update Profile Picture</button>
                        </form>
                        <form id="updateCoverPhotoForm" enctype="multipart/form-data">
                            <input type="hidden" name="update_type" value="cover">
                            <input type="file" name="cover" accept="image/*" required>
                            <button type="submit">Update Cover Photo</button>
                        </form>
                        <div id="coverUpdateMessage"></div>
                        <div id="updateMessage"></div>
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script>
                                $(document).ready(function () {
                                    $('#updateProfilePictureForm').on('submit', function (e) {
                                        e.preventDefault(); // Prevent the default form submission

                                        // Create a FormData object from the form
                                        let formData = new FormData(this);

                                        $.ajax({
                                            url: '../../Backend/user-profile/update_picture.php', // Server-side script
                                            type: 'POST',
                                            data: formData,
                                            processData: false, // Prevent jQuery from automatically transforming the data
                                            contentType: false, // Do not set content type header, it is automatically handled by FormData
                                            success: function (response) {
                                                $('#updateMessage').html('<p style="color: green;">Profile picture updated successfully!</p>');
                                            },
                                            error: function (xhr, status, error) {
                                                $('#updateMessage').html('<p style="color: red;">Error updating profile picture: ' + error + '</p>');
                                            }
                                        });
                                    });
                                });
                                
                                $(document).ready(function () {
                                    $('#updateCoverPhotoForm').on('submit', function (e) {
                                        e.preventDefault(); // Prevent default form submission

                                        let formData = new FormData(this); // Create FormData object from the form

                                        $.ajax({
                                            url: '../../Backend/user-profile/update_cover_photo.php',
                                            type: 'POST',
                                            data: formData,
                                            processData: false, // Do not process data
                                            contentType: false, // Do not set content type
                                            success: function (response) {
                                                let data = JSON.parse(response);
                                                if (data.success) {
                                                    $('#coverUpdateMessage').html('<p style="color: green;">' + data.message + '</p>');
                                                } else {
                                                    $('#coverUpdateMessage').html('<p style="color: red;">' + data.message + '</p>');
                                                }
                                            },
                                            error: function () {
                                                $('#coverUpdateMessage').html('<p style="color: red;">An error occurred while updating the cover photo.</p>');
                                            }
                                        });
                                    });
                                });

                                $(document).ready(function () {
                                    // Open modal when the button is clicked
                                    $('#editInfoButton').on('click', function () {
                                        $('#editInfoModal').fadeIn();
                                    });

                                    // Close modal when the close button is clicked
                                    $('.close-modal').on('click', function () {
                                        $('#editInfoModal').fadeOut();
                                    });

                                    // Submit the form via AJAX
                                    $('#editInfoForm').on('submit', function (e) {
                                        e.preventDefault(); // Prevent default form submission

                                        const formData = $(this).serialize(); // Serialize form data

                                        $.ajax({
                                            url: '../../Backend/user-profile/update_user_info.php', // Backend script
                                            type: 'POST',
                                            data: formData,
                                            success: function (response) {
                                                const data = JSON.parse(response);
                                                if (data.success) {
                                                    // Update the information on the page
                                                    $('#location').text(data.location);
                                                    $('#phone').text(data.phone);
                                                    $('#editInfoModal').fadeOut(); // Close the modal
                                                    alert('Information updated successfully!');
                                                } else {
                                                    alert('Error: ' + data.message);
                                                }
                                            },
                                            error: function () {
                                                alert('An error occurred while updating the information.');
                                            }
                                        });
                                    });
                                });

                                $(document).ready(function () {
                                    // Open the modal when the "Edit Bio" button is clicked
                                    $('#editBioButton').on('click', function () {
                                        $('#editBioModal').fadeIn();
                                    });

                                    // Close the modal when the close button is clicked
                                    $('.close-modal').on('click', function () {
                                        $('#editBioModal').fadeOut();
                                    });

                                    // Submit the form via AJAX
                                    $('#editBioForm').on('submit', function (e) {
                                        e.preventDefault(); // Prevent default form submission

                                        const formData = $(this).serialize(); // Serialize form data

                                        $.ajax({
                                            url: '../../Backend/user-profile/update_user_bio.php', // Backend script
                                            type: 'POST',
                                            data: formData,
                                            success: function (response) {
                                                const data = JSON.parse(response);
                                                if (data.success) {
                                                    // Update the bio on the page
                                                    $('#bio').text(data.bio);
                                                    $('#editBioModal').fadeOut(); // Close the modal
                                                    alert('Bio updated successfully!');
                                                } else {
                                                    alert('Error: ' + data.message);
                                                }
                                            },
                                            error: function () {
                                                alert('An error occurred while updating the bio.');
                                            }
                                        });
                                    });
                                });




                            </script>

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
                        <p><strong>Location:</strong> <span><?php echo htmlspecialchars($location); ?></span></p>
                        <p><strong>Phone number:</strong> <span><?php echo htmlspecialchars($phone); ?></span></p>
                        <p><strong>Date Joined:</strong> <span><?php echo htmlspecialchars($date_joined); ?></span></span></p>
                        <button id="editInfoButton">Edit Info</button>
                    </div>

                    <!-- Edit Info Modal -->
                    <div id="editInfoModal" class="edit-info-modal" style="display: none;">
                        <div class="edit-info-modal-content">
                            <span class="close-edit-info-modal">&times;</span>
                            <h3>Edit Information</h3>
                            <form id="editInfoForm">
                                <label for="editLocation">Location:</label>
                                <input type="text" id="editLocation" name="location" value="<?php echo htmlspecialchars($location); ?>" required>

                                <label for="editPhone">Phone Number:</label>
                                <input type="text" id="editPhone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>

                                <button type="submit">Save Changes</button>
                            </form>
                        </div>
                    </div>
                    <!-- Stats Section -->
                    <div class="stats">
                        <p><strong>Bio:</strong> <span id="bio"><?php echo htmlspecialchars($bio); ?></span></p>
                        <button id="editBioButton">Edit Bio</button>
                    </div>

                    <!-- Edit Bio Modal -->
                    <div id="editBioModal" class="edit-bio-modal" style="display: none;">
                        <div class="edit-bio-modal-content">
                            <span class="close-edit-bio-modal">&times;</span>
                            <h3>Edit Bio</h3>
                            <form id="editBioForm">
                                <label for="editBio">Bio:</label>
                                <textarea id="editBio" name="bio" rows="4" required><?php echo htmlspecialchars($bio); ?></textarea>
                                <button type="submit">Save Changes</button>
                            </form>
                        </div>
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
                        <a href="#" class="tab-link active" data-tab="post">Livestock Post</a>
                        <a href="#" class="tab-link" data-tab="forum">Livestock Forum</a>
                        <a href="#" class="tab-link" data-tab="feedback">Feedback & Ratings</a>
                    </nav>

                    <!-- Livestock Post Section -->
                    <div class="tab-content">
                        <div id="post" class="tab-pane active">
                            <div class="listings">
                                <?php include('../../Backend/livestock_posts/bring_it_alive.php'); ?>
                                <?php foreach ($no_lives_matter as $row): ?>
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

                                        <div class="post-date"><?= date('F j, Y, g:i A', strtotime($row['date_posted'])) ?></div>

                                        <div class="listing-image">
                                            <img src="<?= !empty($row['image_posts']) && file_exists('../../uploads/livestock_posts/' . $row['image_posts'])
                                                            ? '../../uploads/livestock_posts/' . htmlspecialchars($row['image_posts'])
                                                            : $default_image ?>" alt="Livestock Image" class="livestock-img">
                                        </div>

                                        <div class="card-details">
                                            <div class="card-info" id="declaration-of-chat">
                                                <div class="animal-type"><?= $row['livestock_type'] ?></div>

                                                <div class="card-bottom-info">
                                                    <div class="livestock-title"> <strong><?= $row['title'] ?></strong></div>
                                                    <div class="price"> <strong>$<?= $row['price'] ?></strong> /Head</div>
                                                </div>
                                            </div>

                                            <div class="card-more-info">
                                                <div class="info-item">
                                                    <i class="fas fa-box"></i> <!-- Icon for Quantity -->
                                                    <?= $row['quantity'] ?>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-paw"></i> <!-- Icon for Breed -->
                                                    <?= $row['breed'] ?>
                                                </div>
                                                <div class="info-item">
                                                    <i class="fas fa-weight-hanging"></i> <!-- Icon for Weight -->
                                                    <?= $row['weight'] ?> km
                                                </div>
                                                <div class="view-button">
                                                    <a href="../../Frontend/Pages/details-page.php?post_id=<?php echo $row['post_id']; ?>&why_are_here=profile" class="view-button-link">
                                                        <button type="button">VIEW</button>
                                                    </a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                <?php endforeach; ?>


                            </div>
                        </div>
                        <div id="forum" class="tab-pane">
                        <?php include('../../Backend/livestock_posts/every_one_is_dead.php'); ?>
                        <?php foreach ($no_lives_matter as $row): ?>
                            <img src="../../uploads/profile_pictures/<?= $row['profile_picture']?>" alt="Livestock Image" class="livestock-img">
                            <p><?= $row['first_name'] ?> <?= $row['last_name'] ?></p>
                            <h1><?= $row['title'] ?></h1>
                            <p><?= $row['description'] ?></p>
                        <?php endforeach; ?>
                        </div>
                        <div id="feedback" class="tab-pane">
                            <p>Content for Feedback & Ratings</p>
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

    document.addEventListener("DOMContentLoaded", () => {
        const links = document.querySelectorAll(".tab-link");
        const panes = document.querySelectorAll(".tab-pane");

        links.forEach(link => {
            link.addEventListener("click", (e) => {
                e.preventDefault();

                // Remove active class from all links and panes
                links.forEach(item => item.classList.remove("active"));
                panes.forEach(pane => pane.classList.remove("active"));

                // Add active class to the clicked link and associated pane
                const target = document.getElementById(link.dataset.tab);
                link.classList.add("active");
                target.classList.add("active");
            });
        });
    });
</script>

</html>