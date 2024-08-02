<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../../Frontend/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX - Dashboard</title>
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
            $page = 'dashboard';
            include('../../sidebar/sidebar-admin.php');
        ?>
        <div class="main-content">
            <header>
                <div class="logo">LivestoX Logo Here</div>
                <div class="search">
                    <input type="text" placeholder="Search Livestock">
                </div>
            </header>
            <div class="listings">
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 7 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 22 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>
                <div class="listing-card">
                    <div class="listing-image"></div>
                    <div class="listing-details">
                        <div class="listing-info">
                            <div class="farmer-name">Farmer 9 FullName</div>
                            <div class="post-date">July 19, 2024 at 10:02 am</div>
                            <div class="description">Livestock Descriptions</div>
                        </div>
                        <div class="actions">
                            <div class="likes">
                                5.0 ⭐⭐⭐⭐⭐ Livestock Ratings (1.1k)
                            </div>
                            <button class="chat-button">CHAT</button>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</body>

<script src="../../JS/logout-confirmation.js"></script>
</html>
