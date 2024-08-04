<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivstoX Sign Up Form</title>
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="welcome-text">
                <h1>Sign Up</h1>
                <form action="../Backend/login/signup.php" method="POST">
                    <p>How will you use LivstoX?</p>
                    <div class="radio-group">
                        <label>
                            <input type="radio" value="farmer" name="role" required>
                            Livestock Farmers
                        </label>
                        <label>
                            <input type="radio" value="buyer" name="role"required>
                            Livestock Buyers
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="FirstName" name="fname" required>
                        <input type="text" placeholder="LastName" name="lname" required>
                    </div>
                    <div class="input-group">
                        <input type="text" placeholder="Username" name="username" value="" required>
                        <input type="tel" placeholder="Phone Number 63+" name="phone" value="" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                    </div>
                    <input type="email" placeholder="Email" name="email" value="" required>
                    <input type="password" placeholder="Password" name="password" value="" required>
                    <input type="password" placeholder="Confirm Password" name="password2" value="" required>
                    <input type="submit" name="submit" value="Sign Up">
                </form>
                <div class="login">
                    <p>Already have an account? <a href="login.php">Log In</a></p>
                </div>                
            </div>
        </div>
        <div class="right-side">
            <div class="overlay"></div>
            <img src="../Assets/Cow-Gang.jpg" alt="Cow Gang">
            <div class="centered-text">
                <h2>Welcome to LivestoX</h2>
                <p>Your trusted Livestock Online Marketplace</p>
                <p class="message-footer" > Healthy livestock, thriving farms our animals are the heart of our fields.</p>
            </div>
        </div>
    </div>
</body>
</html>
