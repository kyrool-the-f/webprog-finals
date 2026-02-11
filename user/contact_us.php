<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Car Rentals</title>
    <link rel="stylesheet" href="contact_us1.css">
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
                        <a href="#" onclick="openSignInModal(); return false;">Sign In</a>
                        <a href="#" onclick="openRegisterModal(); return false;">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <section id="contact-hero">
            <h1>Get in Touch</h1>
            <p>Questions about renting a car or booking a chauffeur? We're here to help so just reach out and we'll reply promptly.</p>
        </section>

        <div class="contact-grid">
            <div class="contact-card contact-details">
                <h2>Contact Details</h2>
                <p><strong>Phone:</strong> +63 912 345 6789</p>
                <p><strong>Email:</strong> info@caloocars.example</p>
                <p><strong>Address:</strong> 6767 Main Street, Caloocan</p>

                <h3>Hours</h3>
                <p>Mon–Fri: 8:00 AM – 8:00 PM<br>Sat–Sun: 9:00 AM – 5:00 PM</p>
            </div>

            <div class="contact-card contact-form">
                <h2>Send Us a Message</h2>
                <form id="contactForm" method="post" action="#">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" placeholder="" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>

                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+63" required>

                    <label for="service">Service</label>
                    <select id="service" name="service">
                        <option>Car Rental</option>
                        <option>Chauffeur Service</option>
                        <option>Feedback</option>
                        <option>Other</option>
                    </select>

                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Tell us what you need" required></textarea>

                    <div class="form-actions">
                        <?php if (isset($_SESSION['logged_in'])): ?>
                            <button type="submit" class="btn-primary">Send Message</button>
                        <?php else: ?>
                            <button type="button" class="btn-primary" onclick="openSignInModal()">Send Message</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

    <footer>
        <p>&copy; 2023 Car Rentals. All rights reserved.</p>
    </footer>

    <script src="modal.js"></script>
    <script src="user_register.js"></script>
    <script src="user_sign_in.js"></script>
    
    <script>
        window.isLoggedIn = <?php echo isset($_SESSION['logged_in']) && $_SESSION['logged_in'] ? 'true' : 'false'; ?>;

        document.getElementById("contactForm").addEventListener("submit", function (e) {
            e.preventDefault();

            if (!window.isLoggedIn) {
                openSignInModal();
                return;
            }

            const formData = new FormData(this);

            fetch("contact_submit.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Message sent successfully!");
                    this.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Something went wrong.");
            });
        });
    </script>
</body>
</html>