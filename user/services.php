<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rentals</title>
    <link rel="stylesheet" href="services1.css">
</head>
<body>

<?php 
session_start();
include "config.php"; 

$isLoggedIn = isset($_SESSION['logged_in']);
$userName = $_SESSION['user_name'] ?? null;
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
                        <a href="#" onclick="openSignInModal(); return false;">Sign In</a>
                        <a href="#" onclick="openRegisterModal(); return false;">Register</a>
                    <?php endif; ?>
                </div>
            </div>        
        </div>
    </nav>
    <header>
        <h1>Welcome to Our Car Rental Service</h1>
    </header>
    
    <div class="container">
        <section id="Self-drive" class="collapsible-section">
            <div class="section-header" onclick="toggleDropdown(this)">
                <h2>Self Drive</h2>
            <p>One of the most common and in-demand form of car rental is the – Self-drive Car Rentals.</p>
                <span class="toggle-icon">+</span>
            </div>
            <div class="section-content">
                <ul>
                    <li>Drive at your own pace and schedule</li>
                    <li>Wide selection of vehicles to choose from</li>
                    <li>Flexible pickup and dropoff locations</li>
                    <li>24/7 roadside assistance available</li>
                    <li>Affordable rates for daily, weekly, or monthly rentals</li>
                </ul>
            </div>
        </section>

        <section id="Chauffeur-driven" class="collapsible-section">
            <div class="section-header" onclick="toggleDropdown(this)">
                <h2>Chauffeur-driven</h2>
            <p>Chauffeur-driven car rentals are becoming increasingly popular among travelers who prefer to sit back and relax while someone else takes care of the driving.</p>
                <span class="toggle-icon">+</span>
            </div>
            <div class="section-content">
                <ul>
                    <li>Professional and courteous drivers</li>
                    <li>Premium vehicles for comfortable travel</li>
                    <li>Perfect for business trips and special occasions</li>
                    <li>Customizable routes and schedules</li>
                    <li>Door-to-door service with personalized attention</li>
                </ul>
            </div>
        </section>
    </div>

    <div class="form">
        <section id="booking" class="booking">
            <h1>Rent Your Car Now</h1>
            <p class="booking-sub">Choose your preferred date and we will be happy to assist you!</p>
            <form id="submit-form" class="booking-form" action="submit_form.php" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full name</label>
                        <input type="text" name="fullname" placeholder="Full name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="tel" name="phone" placeholder="Contact Number" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Upload Driver's License</label>
                        <input type="file" name="picture" accept="image/*" placeholder="Upload your License" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Vehicle Type</label>
                        <select name="cartype" required>
                            <option value="1">SPORTS - Lightning McQueen - (2 Seaters)</option>
                            <option value="2">SEDAN - Vios - (3-4 Seaters)</option>
                            <option value="3">SUV - CR-V - (5-7 Seaters)</option>
                            <option value="4">VAN - Hiace - (8-10 Seaters)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Service Type</label>
                        <select name="service" required>
                            <option value="self-drive">Self Drive</option>
                            <option value="chauffeur-driven">Chauffeur-Driven</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Pickup Location</label>
                        <input type="text" name="pickup_loc" placeholder="Pickup Location" required>
                    </div>
                    <div class="form-group">
                        <label>Pickup Date and Time</label>
                        <input type="datetime-local" name="pickup_dt" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Return Location</label>
                        <input type="text" name="return_loc" placeholder="Return Location">
                    </div>
                    <div class="form-group">
                        <label>Return Date and Time</label>
                        <input type="datetime-local" name="return_dt">
                    </div>
                </div>
                <?php if ($isLoggedIn): ?>
                    <div class="form-submit">
                        <button type="submit" class="btn-quote">Get Car</button>
                    </div>
                <?php else: ?>
                    <div class="form-submit">
                        <button type="button" class="btn-quote" onclick="openSignInModal()">Get Car</button>
                    </div>
                <?php endif; ?>
            </form>
        </section>
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
    <script src="secureSubmit.js"></script>
    <script>
        function toggleDropdown(header) {
            const section = header.parentElement;
            const content = header.nextElementSibling;
            const icon = header.querySelector('.toggle-icon');
            
            section.classList.toggle('active');
            
            if (section.classList.contains('active')) {
                icon.textContent = '−';
                content.style.display = 'block';
            } else {
                icon.textContent = '+';
                content.style.display = 'none';
            }
        };

        window.isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'true' : 'false'; ?>;
    </script>
</body>
</html>