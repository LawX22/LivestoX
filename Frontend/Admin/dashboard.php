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
    <title>LivestoX</title>
    <link rel="stylesheet" href="../../css/farmers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container">
        <?php include('../../sidebar/sidebar-admin.php');?>
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
