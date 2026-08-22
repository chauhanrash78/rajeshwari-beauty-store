<?php

include('auth_check.php');
include "../db.php";

$totalProducts = $conn->query(
    "SELECT COUNT(*) as total FROM products"
)->fetch_assoc()['total'] ?? 0;

$totalUsers = $conn->query(
    "SELECT COUNT(*) as total FROM users"
)->fetch_assoc()['total'] ?? 0;

$totalOrders = $conn->query(
    "SELECT COUNT(*) as total FROM orders"
)->fetch_assoc()['total'] ?? 0;

$latestOrders = $conn->query(
    "SELECT order_id, email, status, payment, created_at
     FROM orders
     ORDER BY id DESC
     LIMIT 6"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rajeshwari Beauty Admin</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    >

    <link rel="stylesheet" href="index.css">
</head>

<body>

<header>

    <div class="admin-logo">

        <img
            src="../logo3.jpg"
            alt="Rajeshwari Beauty"
            class="admin-logo-image"
        >

        <span>
            Rajeshwari Beauty Admin
        </span>

    </div>

    <a
        href="logout.php"
        class="logout-link"
    >
        <i class="fas fa-sign-out-alt"></i>
        Logout
    </a>

</header>

<div class="wrapper">

    <div class="sidebar">

        <a
            href="index.php"
            class="active"
        >
            <i class="fas fa-chart-pie"></i>
            Dashboard
        </a>

        <a href="manage_products.php">
            <i class="fas fa-shopping-bag"></i>
            Manage Products
        </a>

        <a href="customers.php">
            <i class="fas fa-user-check"></i>
            Customers
        </a>

        <a href="orders.php">
            <i class="fas fa-receipt"></i>
            Orders
        </a>

        <a href="report.php">
            <i class="fas fa-file-invoice-dollar"></i>
            Business Report
        </a>

    </div>

    <div class="main-content">

        <div class="dashboard-header">

            <h2>
                Beauty Store Overview
            </h2>

            <p>
                Welcome back! Here is a summary of your shop's performance.
            </p>

        </div>

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="fas fa-box-open"></i>
                </div>

                <div class="stat-info">
                    <h3>
                        <?php echo $totalProducts; ?>
                    </h3>

                    <p>
                        Total Products
                    </p>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>

                <div class="stat-info">
                    <h3>
                        <?php echo $totalUsers; ?>
                    </h3>

                    <p>
                        Total Customers
                    </p>
                </div>

            </div>

            <div class="stat-card">

                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>

                <div class="stat-info">
                    <h3>
                        <?php echo $totalOrders; ?>
                    </h3>

                    <p>
                        Total Orders
                    </p>
                </div>

            </div>

        </div>

        <div class="data-card">

            <h4>

                <span>
                    Recent Orders
                </span>

                <a
                    href="orders.php"
                    class="view-btn"
                >
                    View All
                </a>

            </h4>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Email Address</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($latestOrders->num_rows > 0): ?>

                        <?php while ($order = $latestOrders->fetch_assoc()): ?>

                            <tr>

                                <td class="order-id-cell">
                                    <?php echo htmlspecialchars($order['order_id']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($order['email']); ?>
                                </td>

                                <td class="payment-cell">
                                    <?php echo htmlspecialchars($order['payment']); ?>
                                </td>

                                <td>

                                    <span
                                        class="status <?php echo strtolower($order['status']); ?>"
                                    >
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>

                                </td>

                                <td class="date-cell">
                                    <?php
                                    echo date(
                                        'd M, Y',
                                        strtotime($order['created_at'])
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td
                                colspan="5"
                                class="empty-orders"
                            >
                                No recent orders found.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

</body>
</html>