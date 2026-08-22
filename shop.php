<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Store</title>
    <link rel="stylesheet" href="style.css">

    <style>
        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            width: 100%;
        }

        .top-category {
            position: sticky;
            top: 80px;
            z-index: 999;
            background-color: white;
        }

        .main-container {
            display: flex;
            align-items: flex-start;
        }
    </style>
</head>

<body>

<header>
    <div class="logo-container">
        <img src="logo3.jpg" alt="Rajeshwari Beauty Store Logo" class="site-logo">
        <h1>Rajeshwari Beauty Store</h1>
    </div>

    <div class="menu">
        <div class="search-container-inline">
            <input
                type="text"
                id="searchInput"
                placeholder="🔍 Search..."
                onkeyup="searchProducts()"
            >
        </div>

        <a href="cart.html" class="cart-btn">
            Cart 🛒 <span id="cartCount" class="cart-badge">(0)</span>
        </a>

        <span onclick="openWishlist()" class="nav-item">
            Wishlist ❤️
        </span>

        <a href="orders.html" class="nav-item">
            Orders 📦
        </a>

        <span onclick="logout()" class="nav-item logout-btn">
            Logout 🚪
        </span>
    </div>
</header>

<div class="top-category">
    <span onclick="filterCategory('')">All</span>
    <span onclick="filterCategory('Makeup')">Makeup</span>
    <span onclick="filterCategory('Skincare')">Skincare</span>
    <span onclick="filterCategory('Haircare')">Haircare</span>
    <span onclick="filterCategory('Tools&Brushes')">Tools & Brushes</span>
    <span onclick="filterCategory('Nails')">Nails</span>
</div>

<div class="main-container">

    <div class="sidebar">

        <h3>Brands</h3>

        <label><input type="checkbox" class="brand" value="lakmē"> Lakmé</label>
        <label><input type="checkbox" class="brand" value="swissbeauty"> Swiss Beauty</label>
        <label><input type="checkbox" class="brand" value="glam21"> Glam21</label>
        <label><input type="checkbox" class="brand" value="mars"> Mars</label>
        <label><input type="checkbox" class="brand" value="maybelline"> Maybelline</label>
        <label><input type="checkbox" class="brand" value="loréal"> L'Oréal</label>
        <label><input type="checkbox" class="brand" value="nivea"> Nivea</label>
        <label><input type="checkbox" class="brand" value="himalaya"> Himalaya</label>
        <label><input type="checkbox" class="brand" value="streax"> Streax</label>

        <h3>Subcategory</h3>

        <label><input type="checkbox" class="sub" value="foundation"> Foundation</label>
        <label><input type="checkbox" class="sub" value="primer"> Primer</label>
        <label><input type="checkbox" class="sub" value="concealer"> Concealer</label>
        <label><input type="checkbox" class="sub" value="compact"> Compact</label>
        <label><input type="checkbox" class="sub" value="blusher"> Blusher</label>
        <label><input type="checkbox" class="sub" value="makeupfixer"> Makeup Fixer</label>
        <label><input type="checkbox" class="sub" value="highlighter"> Highlighter</label>
        <label><input type="checkbox" class="sub" value="lipstick"> Lipstick</label>
        <label><input type="checkbox" class="sub" value="kajal"> Kajal</label>
        <label><input type="checkbox" class="sub" value="Eyeliner"> Eyeliner</label>
        <label><input type="checkbox" class="sub" value="mascara"> Mascara</label>
        <label><input type="checkbox" class="sub" value="eyeshadow"> Eyeshadow</label>
        <label><input type="checkbox" class="sub" value="Cream"> Cream</label>
        <label><input type="checkbox" class="sub" value="moisturizer"> Moisturizer</label>
        <label><input type="checkbox" class="sub" value="bodyLotion"> Body Lotion</label>
        <label><input type="checkbox" class="sub" value="sunscreen"> Sunscreen</label>
        <label><input type="checkbox" class="sub" value="face wash"> Face Wash</label>
        <label><input type="checkbox" class="sub" value="face mask"> Face Mask</label>
        <label><input type="checkbox" class="sub" value="cleanser"> Cleanser</label>
        <label><input type="checkbox" class="sub" value="serum"> Serum</label>
        <label><input type="checkbox" class="sub" value="nail Polish"> Nail Polish</label>
        <label><input type="checkbox" class="sub" value="brushes"> Brushes</label>
        <label><input type="checkbox" class="sub" value="shampoo"> Shampoo</label>
        <label><input type="checkbox" class="sub" value="hair serum"> Hair Serum</label>
        <label><input type="checkbox" class="sub" value="Hair Colour"> Hair Colour</label>

    </div>

    <div id="products"></div>

</div>

<script src="script.js?v=3"></script>

</body>
</html>