<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Rajeshwari Beauty Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>

<header>
    <div class="logo-container">
        <img src="logo3.jpg" alt="Rajeshwari Logo" class="site-logo">
        <h1>Rajeshwari Beauty</h1>
    </div>

    <nav class="nav-links">
        <a href="#home">HOME</a>
        <a href="#about">ABOUT US</a>
        <a href="shop.php">SHOP</a>
        <a href="#contact">CONTACT</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i></a>
    </nav>
</header>

<section class="hero" id="home">
    <div class="hero-content">
        <h2>Define Your Beauty</h2>
        <p>Premium Cosmetics, Skincare & Fashion Essentials</p>
        <a href="shop.php" class="shop-btn">Shop Collection</a>
    </div>
</section>

<section class="categories">
    <h2 class="section-title">Shop by Category</h2>

    <div class="cat-grid">
        <div class="cat-item">
            <img src="https://images.pexels.com/photos/2533266/pexels-photo-2533266.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Makeup">
            <div class="cat-info">
                <h3>Professional Makeup</h3>
                <a href="shop.php?category=makeup">Explore Products →</a>
            </div>
        </div>

        <div class="cat-item">
            <img src="https://images.pexels.com/photos/3762871/pexels-photo-3762871.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Skincare">
            <div class="cat-info">
                <h3>Premium Skincare</h3>
                <a href="shop.php?category=skincare">Explore Products →</a>
            </div>
        </div>

        <div class="cat-item">
            <img src="https://images.pexels.com/photos/965989/pexels-photo-965989.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Fragrance">
            <div class="cat-info">
                <h3>Luxury Fragrances</h3>
                <a href="shop.php?category=fragrance">Explore Products →</a>
            </div>
        </div>
    </div>
</section>

<section class="about" id="about">
    <div class="about-img">
        <img src="https://images.pexels.com/photos/1926620/pexels-photo-1926620.jpeg?auto=compress&cs=tinysrgb&w=800" alt="About Model">
    </div>

    <div class="about-text">
        <h2>Our Mission</h2>

        <p>
            Founded with a passion for elegance, Rajeshwari Beauty Store aims to bridge the gap between luxury and accessibility. We curate the finest products from global brands like Lakmē, Maybelline, and Swissbeauty.
        </p>

        <p>
            Humne ye store sirf business ke liye nahi, balki ek beauty community banane ke liye banaya hai. We believe everyone deserves to glow.
        </p>

        <a href="shop.php" class="shop-btn start-shopping-btn">Start Shopping</a>
    </div>
</section>

<footer>
    <div class="footer-grid">

        <div class="footer-col">
            <h3 class="footer-brand">Rajeshwari</h3>

            <p>
                Your ultimate destination for authentic beauty, luxury cosmetics, and fashion needs.
            </p>

            <div class="socials">
                <i class="fab fa-instagram"></i>
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-pinterest-p"></i>
            </div>
        </div>

        <div class="footer-col" id="contact">
            <h3>Contact Us</h3>

            <p class="contact-info">
                <i class="fas fa-map-marker-alt"></i>
                123, Beauty Lane, Krishna Nagar, Ahmedabad, Gujarat
            </p>

            <p class="contact-info">
                <i class="fas fa-phone-alt"></i>
                +91 98765 43210
            </p>

            <p class="contact-info">
                <i class="fas fa-envelope"></i>
                contact@rajeshwaribeauty.com
            </p>
        </div>

        <div class="footer-col">
            <h3>Quick Links</h3>

            <ul>
                <li><a href="shop.php">All Collection</a></li>
                <li><a href="#">New Arrivals</a></li>
                <li><a href="#">Best Sellers</a></li>
                <li><a href="#">Our Story</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h3>Payment Scanner</h3>

            <p>Scan to pay via UPI</p>

            <div class="qr-placeholder">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=RajeshwariBeauty" alt="Payment QR Code">
            </div>
        </div>

    </div>

    <div class="bottom-bar">
        &copy; 2026 Rajeshwari Beauty Store | Developed for Academic Internship Submission
    </div>
</footer>

</body>
</html>