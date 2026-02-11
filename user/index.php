<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rentals</title>
    <link rel="stylesheet" href="main1.css">
</head>
<body>

<?php 
session_start();
include "config.php"; 
?>

    <nav>
        <div class="nav-container">
            <div class="logo-placeholder">
                <img src="Caloocars.png" alt="Caloocar Logo">
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
            </ul>
            <div class="user-status">
                <?php if (isset($_SESSION['logged_in'])): ?>
                    <span class="status-badge logged-in">✓ Logged In</span> <?= htmlspecialchars($_SESSION['user_name']) ?>
                <?php else: ?>
                    <span class="status-badge guest">Guest</span>
                <?php endif; ?>
            </div>
            <div class="user-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <div class="user-dropdown">
                    <?php if (isset($_SESSION['logged_in'])): ?>
                        <a href="user_logout.php">Logout</a>
                    <?php else: ?>
                        <a href="#signin-modal">Sign In</a>
                        <a href="#register-modal">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <section id="home">
            <h1>Welcome to Caloo-Cars!</h1>
            <p>Get the best car rental deals at affordable prices. Drive your dream car today!</p>
        </section>
        <section id="cars">
            <h1>Available Cars</h1>
            <p>Have a fun journey with our well-maintained and reliable selection of cars.</p>
        </section>
    </div>
    <section id="gallery" class="gallery">
  <h2>Our Whips</h2>
  <div class="gallery-grid">
    <figure class="card">
      <img src="car_VIOS.png" alt="Car 1">
      <figcaption>Mirage G4 — Comfortable & efficient</figcaption>
    </figure>
    <figure class="card">
      <img src="car_CRV.png" alt="Car 2">
      <figcaption>Honda CR-V — Travel in style</figcaption>
    </figure>
    <figure class="card">
      <img src="car_HIACE.png" alt="Car 3">
      <figcaption>Toyota Hiace — Spacious for family trips</figcaption>
    </figure>
    <figure class="card">
      <img src="car_McQueen.png" alt="Car 4">
      <figcaption>Lightning McQueen — Gap a mf</figcaption>
    </figure>
  </div>
</section>

    <footer>
        <p>&copy; 2023 Car Rentals. All rights reserved.</p>
    </footer>

<!-- Sign In Modal -->
<div id="signin-modal" class="modal-overlay">
    <div class="modal-box">
        <a href="#" class="modal-close">&times;</a>
        <h2>Sign In</h2>
        <form method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="signin-btn">Sign In</button>
        </form>
    </div>
</div>

<!-- Register Modal -->
<div id="register-modal" class="modal-overlay">
    <div class="modal-box">
        <a href="#" class="modal-close">&times;</a>
        <h2>Create Account</h2>
        <form method="POST" id="registerForm">
            <div class="form-group">
                <label for="reg-firstname">First Name:</label>
                <input type="text" id="reg-firstname" name="firstname" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <label for="reg-middleinitial">Middle Initial:</label>
                <input type="text" id="reg-middleinitial" name="middleinitial" placeholder="Middle Initial">
            </div>
            <div class="form-group">
                <label for="reg-lastname">Last Name:</label>
                <input type="text" id="reg-lastname" name="lastname" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <label for="reg-email">Email:</label>
                <input type="email" id="reg-email" name="email" placeholder="your@email.com" required>
            </div>
            <div class="form-group">
                <label for="reg-password">Password:</label>
                <input type="password" id="reg-password" name="password" placeholder="Create a password" required>
            </div>
            <div class="form-group">
                <label for="reg-confirm">Confirm Password:</label>
                <input type="password" id="reg-confirm" name="confirm" placeholder="Confirm password" required>
            </div>
            <button type="submit" class="signin-btn" name="register">Register</button>
        </form>
    </div>
</div>

    <script src="modal.js"></script>
    <script src="user_register.js"></script>
    <script src="user_sign_in.js"></script>
    
</body>
</html>