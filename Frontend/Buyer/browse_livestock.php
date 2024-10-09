<?php
session_start();
include('../../Backend/db/db_connect.php'); 

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
    <title>LivestoX - Dashboard</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/dashboard.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php 
            $page = 'browse_livestock';
            include('../../sidebar/sidebar-buyer.php');
        ?>
        <div class="main-content">
        <header>
                <div class="logo">LivestoX Logo Here</div>
                <div class="search">
                    <i class="fas fa-filter" onclick="showFilterPopup()"></i>
                    <input type="text" placeholder="Search Livestock">
                </div>
            </header>
           <!-- Filter Popup -->
            <div id="filter-popup" class="filter-popup" style="display:none;">
                <div class="filter-content">
                    <span class="close" onclick="hideFilterPopup()">&times;</span>
                    <h2>Search Livestock For Sale</h2>
                    <div class="auction-options">
                        <button class="auction-btn active">Private Treaty</button>
                        <button class="auction-btn">Online Auctions</button>
                    </div>
                    <div class="animal-icons">
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Bulls">Bulls</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Cows">Cows</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Horses">Horses</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Pigs">Pigs</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Goats">Goats</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Sheep">Sheep</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Alpacas">Alpacas</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Rabbits">Rabbits</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Dogs">Dogs</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Cats">Cats</div>
                        <div class="icon"><img src="../../Assets/default-profile.png" alt="Humans">Humans</div>
                    </div>
                    <div class="filter-search">
                        <select>
                            <option value="">Livestock</option>
                            <option value="cows">Chickens</option>
                            <option value="bulls">Bulls</option>
                            <option value="cows">Cows</option>
                            <option value="cows">Pigs</option>
                            <!-- Add more options as needed -->
                        </select>
                        <select>
                            <option value="">Breed</option>
                            <option value="cows">Roundhead</option>
                            <option value="cows">Hatch</option>
                            <option value="cows">Hatch</option>
                            <option value="cows">Sweater</option>
                            <!-- Add class options here -->
                        </select>
                        <button onclick="applyFilters()">Search</button>
                    </div>
                </div>
            </div>       
            <div class="listings">
                <div class="listing-card">
                    <div class="listing-image">
                        <img src="../../Assets/Livestock.jpg" alt="Livestock Image" class="livestock-img">
                    </div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="livestock-title">Quality Beef Cattle for Sale</div> <!-- Title for livestock -->
                            <div class="farmer-name">Lawrenz Xavier Carisusa</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">
                                <ul>
                                    <li><strong>Animal Type:</strong> Beef Cattle</li>
                                    <li><strong>Breed:</strong> Angus</li>
                                    <li><strong>Quantity Available:</strong> 5</li>
                                    <li><strong>Weight:</strong> Approx. 1,200 lbs each</li>
                                    <li><strong>Health Status:</strong> Vaccinated and dewormed</li>
                                </ul>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                            <button class="details-button">VIEW FULL DETAILS</button> 
                        </div>
                    </div>
                </div>

                <div class="listing-card">
                    <div class="listing-image">
                        <img src="../../Assets/Cow-Gang.jpg" alt="Livestock Image" class="livestock-img">
                    </div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="livestock-title">Dairy Goats for Sale</div> <!-- Title for livestock -->
                            <div class="farmer-name">Farmer Jane Doe</div>
                            <div class="post-date">September 20, 2024 at 11:15 am</div>
                            <div class="description">
                                <ul>
                                    <li><strong>Animal Type:</strong> Dairy Goat</li>
                                    <li><strong>Breed:</strong> Nubian</li>
                                    <li><strong>Quantity Available:</strong> 8</li>
                                    <li><strong>Age:</strong> 1 year</li>
                                    <li><strong>Health Status:</strong> Fully vaccinated</li>
                                </ul>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                4.8 ⭐⭐⭐⭐⭐ Livestock Ratings (700)
                            </div>
                            <button class="chat-button">CHAT</button>
                            <button class="details-button">VIEW FULL DETAILS</button> 
                        </div>
                    </div>
                </div>

                <div class="listing-card">
                    <div class="listing-image">
                        <img src="../../Assets/Goat-Gang.jpg" alt="Livestock Image" class="livestock-img">
                    </div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="livestock-title">Heritage Pork for Sale</div> <!-- Title for livestock -->
                            <div class="farmer-name">Farmer Mike Smith</div>
                            <div class="post-date">September 22, 2024 at 3:30 pm</div>
                            <div class="description">
                                <ul>
                                    <li><strong>Animal Type:</strong> Pig</li>
                                    <li><strong>Breed:</strong> Berkshire</li>
                                    <li><strong>Quantity Available:</strong> 10</li>
                                    <li><strong>Weight:</strong> Approx. 250 lbs each</li>
                                    <li><strong>Health Status:</strong> Certified organic</li>
                                </ul>
                            </div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.5k)
                            </div>
                            <button class="chat-button">CHAT</button>
                            <button class="details-button">VIEW FULL DETAILS</button> 
                        </div>
                    </div>
                </div>

                <!-- Repeat for more listings -->
                 
            </div>
        </div>
    </div>
    
</body>

<script src="../../js/logout-confirmation.js"></script>
<script src="../../js/filtering.js"></script>
</html>
