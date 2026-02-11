<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rentals</title>
    <link rel="stylesheet" href="about_us1.css">
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

    <div class="services-explanation">
        <h2>Our Services</h2>
        <div class="services-content">
            <div class="service-item">
                <h3>Car Rental</h3>
                <p>We offer a wide selection of premium vehicles for short-term and long-term rentals. Whether you need a comfortable sedan, spacious SUV, or luxury vehicle, we have the perfect car to meet your needs. Our fleet is regularly maintained and fully insured for your peace of mind.</p>
            </div>
            <div class="service-item">
                <h3>Professional Chauffeur Services</h3>
                <p>Our experienced and courteous chauffeurs provide professional transportation services for business meetings, corporate events, airport transfers, and special occasions. Sit back and relax while our trained drivers handle the road, allowing you to focus on what matters most.</p>
            </div>
        </div>
        <p class="services-closing">Whether you're looking for independence with our car rental options or premium convenience with our chauffeur services, we're committed to providing exceptional customer service and a smooth travel experience every time.</p>
    </div>

    <div class="container" style="margin: 40px auto; max-width: 1000px; text-align: center;">
        <h1>About Us</h1>
        <p>Meet the talented developers who created this website.</p>
        
        <div class="developers-grid">
            <div class="developer-card">
                <div class="developer-image">
                    <img src="madriaga.png" alt="Kal">
                </div>
                <div class="developer-info">
                    <h3>Francis Vince Madriaga</h3>
                    <p>HTML Developer</p>
                </div>
            </div>

            <div class="developer-card">
                <div class="developer-image">
                    <img src="vallester.png" alt="Kai">
                </div>
                <div class="developer-info">
                    <h3>Kyroll William Vallester</h3>
                    <p>Database Specialist</p>
                </div>
            </div>

            <div class="developer-card">
                <div class="developer-image">
                    <img src="cruz.png" alt="Cruz">
                </div>
                <div class="developer-info">
                    <h3>Carl Benzon Cruz</h3>
                    <p>Javascript Engineer</p>
                </div>
            </div>

            <div class="developer-card">
                <div class="developer-image">
                    <img src="cabrera.png" alt="Cris">
                </div>
                <div class="developer-info">
                    <h3>Crisaldo Cabrera</h3>
                    <p>UI/UX Designer</p>
                </div>
            </div>
        </div>
    </div>

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
                <label for="reg-name">First Name:</label>
                <input type="text" id="reg-name" name="firstname" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <label for="reg-name">Middle Initial:</label>
                <input type="text" id="reg-name" name="middleinitial" placeholder="Middle Initial">
            </div>
            <div class="form-group">
                <label for="reg-name">Last Name:</label>
                <input type="text" id="reg-name" name="lastname" placeholder="Last Name" required>
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