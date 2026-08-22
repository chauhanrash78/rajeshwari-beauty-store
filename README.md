# Rajeshwari Beauty Store

A PHP and MySQL based beauty e-commerce website with a customer shopping interface and a separate admin management panel.

## About the Project

Rajeshwari Beauty Store is a web-based e-commerce application developed as an academic/internship project.

The application provides a complete beauty shopping workflow for customers and a management dashboard for administrators.

## Features

### Customer Side

* User Registration and Login
* Forgot Password and Password Reset
* Product Browsing
* Product Search
* Category and Brand Filtering
* Product Details
* Wishlist Management
* Shopping Cart
* Product Quantity Management
* Checkout
* Delivery Details
* Payment Method Interface
* Order Placement
* Order History
* Order Tracking
* Order Cancellation

### Admin Side

* Admin Login
* Session-based Authentication
* Dashboard
* Product Management

  * Add Products
  * Update Products
  * Delete Products
  * Product Image Upload
  * Product Search
  * Price Filtering
* Customer Management

  * Search Customers
  * Customer Order Count
  * Customer Spending
  * Customer Classification
  * Sorting and Pagination
* Order Management

  * View Orders
  * Search Orders
  * Filter Orders by Status
  * Update Order Status
  * Pagination
* Business Reports

  * Daily Revenue
  * Weekly Revenue
  * Monthly Revenue
  * Lifetime Revenue
  * Sales Trend Chart
  * Top Customers
  * Best-Selling Products

## Technologies Used

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* XAMPP
* Chart.js
* Font Awesome

## Project Structure

```text
rajeshwari-beauty-store/
│
├── Customer Side
│   ├── Home and Shop
│   ├── Authentication
│   ├── Product Pages
│   ├── Wishlist
│   ├── Cart
│   ├── Checkout
│   ├── Orders
│   └── PHP APIs
│
├── images/
│   ├── Store Logo
│   └── Product Images
│
├── db.php
│
└── admin/
    ├── Dashboard
    ├── Product Management
    ├── Customer Management
    ├── Order Management
    └── Business Reports
```

## Database

The application uses MySQL.

**Database Name:** `beauty_store_m`

Main tables:

* `users`
* `products`
* `orders`

## How to Run Locally

### Requirements

* XAMPP
* Apache
* MySQL
* Web Browser

### Setup

1. Install XAMPP.
2. Start **Apache** and **MySQL**.
3. Place the project inside the XAMPP `htdocs` folder:

```text
C:\xampp\htdocs\rajeshwari-beauty-store
```

4. Open phpMyAdmin:

```text
http://localhost/phpmyadmin/
```

5. Create a database named:

```text
beauty_store_m
```

6. Import or create the required project tables and data.
7. Check the database settings in `db.php`.
8. Open the customer website:

```text
http://localhost/rajeshwari-beauty-store/
```

9. Open the admin panel:

```text
http://localhost/rajeshwari-beauty-store/admin/login.php
```

## Important Notes

* This project is designed to run locally using XAMPP and MySQL.
* PHP files require a PHP-enabled server such as Apache.
* Product images are stored in the `images` directory.
* The database must be configured locally before running the application.
* Do not add real passwords, API keys, payment credentials, or other sensitive information to a public repository.

## Project Purpose

This project demonstrates the practical development of a PHP and MySQL based e-commerce application with separate customer and administrator workflows.

## Author

**Rashmi Chauhan**

## Repository

[GitHub Repository](https://github.com/chauhanrash78/rajeshwari-beauty-store)
