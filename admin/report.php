<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../db.php";

// Revenue
$growth = $conn->query(
    "SELECT
        SUM(
            CASE
                WHEN DATE(o.created_at) = CURDATE()
                THEN o.quantity * p.price
                ELSE 0
            END
        ) AS daily_rev,

        SUM(
            CASE
                WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                THEN o.quantity * p.price
                ELSE 0
            END
        ) AS weekly_rev,

        SUM(
            CASE
                WHEN o.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                THEN o.quantity * p.price
                ELSE 0
            END
        ) AS monthly_rev,

        SUM(o.quantity * p.price) AS total_lifetime_rev

     FROM orders o
     JOIN products p ON o.product_id = p.id"
)->fetch_assoc();

// Top customers
$loyal_customers = $conn->query(
    "SELECT
        u.name,
        o.email,
        COUNT(o.id) AS order_count,
        SUM(o.quantity * p.price) AS total_spent

     FROM orders o
     JOIN products p ON o.product_id = p.id
     JOIN users u ON o.email = u.email

     GROUP BY o.email
     ORDER BY total_spent DESC
     LIMIT 5"
);

// Best-selling products
$top_products = $conn->query(
    "SELECT
        p.name,
        p.brand,
        SUM(o.quantity) AS total_sold

     FROM orders o
     JOIN products p ON o.product_id = p.id

     GROUP BY o.product_id
     ORDER BY total_sold DESC
     LIMIT 5"
);

// Sales trend for current and previous month
$trend = $conn->query(
    "SELECT
        DATE(o.created_at) AS order_date,
        SUM(o.quantity * p.price) AS revenue

     FROM orders o
     JOIN products p ON o.product_id = p.id

     WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)

     GROUP BY DATE(o.created_at)
     ORDER BY order_date ASC"
);

$dates = [];
$revenues = [];

while ($row = $trend->fetch_assoc()) {
    $dates[] = date("d M", strtotime($row['order_date']));
    $revenues[] = (float) $row['revenue'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reports | Rajeshwari Beauty Admin</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="report.css">
</head>

<body>

<div class="header-fixed">

    <div class="top-line">
        Rajeshwari Beauty Admin Panel
    </div>

    <div class="navbar">

        <div class="brand">
            <img
                src="../logo3.jpg"
                alt="Rajeshwari Beauty"
                class="brand-logo"
            >

            <div class="brand-text">
                <span>Rajeshwari</span>
                <strong>Beauty</strong>
            </div>
        </div>

        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="manage_products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="customers.php">Customers</a>
            <a href="report.php" class="active">Report</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>

    </div>

</div>

<div class="container">

    <div class="page-heading">

        <div>
            <span class="page-label">STORE ANALYTICS</span>

            <h1>Performance Overview</h1>

            <p>
                Track revenue, customers and product performance.
            </p>
        </div>

        <button
            class="print-btn"
            onclick="window.print()"
        >
            <i class="fas fa-print"></i>
            Print / Save PDF
        </button>

    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>

            <div>
                <span class="stat-label">Today's Sales</span>

                <h2>
                    ₹<?= number_format($growth['daily_rev'] ?? 0, 2) ?>
                </h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>

            <div>
                <span class="stat-label">Last 7 Days</span>

                <h2>
                    ₹<?= number_format($growth['weekly_rev'] ?? 0, 2) ?>
                </h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-column"></i>
            </div>

            <div>
                <span class="stat-label">Last 30 Days</span>

                <h2>
                    ₹<?= number_format($growth['monthly_rev'] ?? 0, 2) ?>
                </h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-wallet"></i>
            </div>

            <div>
                <span class="stat-label">Lifetime Revenue</span>

                <h2>
                    ₹<?= number_format($growth['total_lifetime_rev'] ?? 0, 2) ?>
                </h2>
            </div>
        </div>

    </div>

    <div class="chart-panel">

        <div class="panel-heading">

            <div>
                <h2>
                    <i class="fas fa-chart-area"></i>
                    Sales Overview
                </h2>

                <p>
                    Revenue trend for the current and previous month
                </p>
            </div>

            <span class="trend-badge">
                <i class="fas fa-calendar"></i>
                Last 60 Days
            </span>

        </div>

        <div class="chart-area">
            <canvas id="salesChart"></canvas>
        </div>

    </div>

    <div class="report-grid">

        <div class="report-panel">

            <div class="panel-heading">

                <div>
                    <h2>
                        <i class="fas fa-crown"></i>
                        Top Customers
                    </h2>

                    <p>
                        Highest spending customers
                    </p>
                </div>

                <span class="panel-count">
                    Top 5
                </span>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Total Spend</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($loyal_customers->num_rows > 0): ?>

                        <?php while ($row = $loyal_customers->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <div class="customer-cell">

                                        <div class="avatar">
                                            <?= strtoupper(substr($row['name'], 0, 1)) ?>
                                        </div>

                                        <div>
                                            <strong>
                                                <?= htmlspecialchars($row['name']) ?>
                                            </strong>

                                            <small>
                                                <?= htmlspecialchars($row['email']) ?>
                                            </small>
                                        </div>

                                    </div>
                                </td>

                                <td>
                                    <span class="orders-badge">
                                        <?= $row['order_count'] ?>
                                    </span>
                                </td>

                                <td>
                                    <strong class="money">
                                        ₹<?= number_format($row['total_spent'], 2) ?>
                                    </strong>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="empty-state">
                                No customer data available.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="report-panel">

            <div class="panel-heading">

                <div>
                    <h2>
                        <i class="fas fa-fire"></i>
                        Best Sellers
                    </h2>

                    <p>
                        Most purchased products
                    </p>
                </div>

                <span class="panel-count">
                    Top 5
                </span>

            </div>

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Brand</th>
                            <th>Sold</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($top_products->num_rows > 0): ?>

                        <?php while ($p = $top_products->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    <strong class="product-title">
                                        <?= htmlspecialchars($p['name']) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="brand-name">
                                        <?= htmlspecialchars($p['brand']) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="sold-badge">
                                        <?= $p['total_sold'] ?> Units
                                    </span>
                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="3" class="empty-state">
                                No product data available.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>
    const labels = <?= json_encode($dates) ?>;
    const values = <?= json_encode($revenues) ?>;

    const chartCanvas = document.getElementById("salesChart");

    new Chart(chartCanvas, {
        type: "line",

        data: {
            labels: labels,

            datasets: [
                {
                    label: "Sales Revenue",
                    data: values,
                    borderColor: "#ff3f6c",
                    backgroundColor: "rgba(255, 63, 108, 0.08)",
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: "#ff3f6c",
                    pointBorderColor: "#ffffff",
                    pointBorderWidth: 2
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    display: false
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return " ₹" +
                                Number(context.raw)
                                    .toLocaleString("en-IN");
                        }
                    }
                }
            },

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        color: "#888",

                        callback: function(value) {
                            return "₹" + value;
                        }
                    },

                    grid: {
                        color: "#f1f1f1"
                    }
                },

                x: {
                    ticks: {
                        color: "#888"
                    },

                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>

</body>
</html>