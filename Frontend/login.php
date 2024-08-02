<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoX Log-In Form</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
         <?php
            if (isset($_SESSION['status'])) {
            ?>
                <div class="text-center">
                    <div class="alert">
                            <?php echo $_SESSION['status']; ?>
                        <button class="close-button" onclick="this.parentElement.style.display='none';">&times;</button>
                    </div>
                </div>
                <?php
                unset($_SESSION['status']);
            }
            ?>

            <div class="welcome-text">
                <h1>Welcome back</h1>
                <p>Please enter your details.</p>
            </div>
            <form action="../Backend/login/login.php" method="POST">
                <input type="text" placeholder="Username" name="username" required>
                <input type="password" placeholder="Password" name="password" required>
                <input type="submit" name="submit" value="Sign In">
            </form>
            <div class="signup">
                <p>Don’t have an account? <a href="signup.php">Create Account</a></p>
            </div>            
        </div>
        <div class="right-side">
            <img src="../Assets/Livestock.jpg" alt="Cow Gang">
            <div class="overlay"></div>
            <div class="centered-text">
                <h2>Welcome Back to LivestoX</h2>
                <p>Your trusted Livestock  Online Marketplace</p>
                <p class="message-footer" > Healthy livestock, thriving farms our animals are the heart of our fields.</p>
            </div>
        </div>
    </div>
</body>
</html>