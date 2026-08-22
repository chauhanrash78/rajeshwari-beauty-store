<?php

include('auth_check.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../db.php";

// Update order status
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int) $_GET['id'];
    $status = $conn->real_escape_string($_GET['status']);

    $conn->query(
        "UPDATE orders SET status='$status' WHERE id=$id"
    );

    header("Location: orders.php");
    exit;
}

// Pagination, search and filter
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search'])
    ? $conn->real_escape_string($_GET['search'])
    : "";

$filter = isset($_GET['filter'])
    ? $conn->real_escape_string($_GET['filter'])
    : "";

$conditions = [];

if (!empty($search)) {
    $conditions[] = "o.order_id LIKE '%$search%'";
}

if (!empty($filter)) {
    $conditions[] = "o.status = '$filter'";
}

$where_clause = "";

if (count($conditions) > 0) {
    $where_clause = " WHERE " . implode(" AND ", $conditions);
}

// Pagination count
$total_results = $conn->query(
    "SELECT COUNT(*) as id
     FROM orders o
     $where_clause"
)->fetch_assoc()['id'];

$total_pages = ceil($total_results / $limit);

// Order statistics
$total_count = $conn->query(
    "SELECT COUNT(*) as total FROM orders"
)->fetch_assoc()['total'];

$delivered_count = $conn->query(
    "SELECT COUNT(*) as delivered
     FROM orders
     WHERE status='Delivered'"
)->fetch_assoc()['delivered'];

$pending_count = $conn->query(
    "SELECT COUNT(*) as pending
     FROM orders
     WHERE status='Pending'"
)->fetch_assoc()['pending'];

$cancelled_count = $conn->query(
    "SELECT COUNT(*) as cancelled
     FROM orders
     WHERE status='Cancelled'"
)->fetch_assoc()['cancelled'];

// Fetch orders
$res = $conn->query(
    "SELECT
        o.*,
        p.name AS product_name,
        p.image,
        p.price,
        u.name AS user_name
     FROM orders o
     JOIN products p ON o.product_id = p.id
     LEFT JOIN users u ON o.email = u.email
     $where_clause
     ORDER BY o.id DESC
     LIMIT $limit OFFSET $offset"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Panel | Rajeshwari Beauty</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="orders.css">
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
            <a href="orders.php" class="active">Orders</a>
            <a href="customers.php">Customers</a>
            <a href="report.php">Report</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>

    </div>

</div>

<div class="container">

    <div class="stats-bar">

        <a
            href="orders.php"
            class="stat-item <?= empty($filter) ? 'active-filter' : '' ?>"
        >
            <i class="fas fa-list"></i>

            <div>
                <h3><?= $total_count ?></h3>
                <p>Total Orders</p>
            </div>
        </a>

        <a
            href="?filter=Pending"
            class="stat-item <?= ($filter == 'Pending') ? 'active-filter' : '' ?>"
        >
            <i class="fas fa-clock"></i>

            <div>
                <h3><?= $pending_count ?></h3>
                <p>Pending</p>
            </div>
        </a>

        <a
            href="?filter=Delivered"
            class="stat-item <?= ($filter == 'Delivered') ? 'active-filter' : '' ?>"
        >
            <i class="fas fa-check-circle"></i>

            <div>
                <h3><?= $delivered_count ?></h3>
                <p>Delivered</p>
            </div>
        </a>

        <a
            href="?filter=Cancelled"
            class="stat-item <?= ($filter == 'Cancelled') ? 'active-filter' : '' ?>"
        >
            <i class="fas fa-times-circle"></i>

            <div>
                <h3><?= $cancelled_count ?></h3>
                <p>Cancelled</p>
            </div>
        </a>

    </div>

    <div class="controls-row">

        <div class="showing-text">
            Showing:
            <?= empty($filter) ? 'All Orders' : $filter ?>
        </div>

        <form class="search-form" method="GET">

            <input
                type="hidden"
                name="filter"
                value="<?= htmlspecialchars($filter) ?>"
            >

            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search Order ID..."
                value="<?= htmlspecialchars($search) ?>"
            >

            <button
                type="submit"
                class="search-btn"
            >
                <i class="fas fa-search"></i>
                Search
            </button>

            <?php if (!empty($search) || !empty($filter)): ?>
                <a
                    href="orders.php"
                    class="action-btn btn-undo reset-btn"
                >
                    Reset
                </a>
            <?php endif; ?>

        </form>

    </div>

    <div class="table-card">

        <div class="table-title">

            <span>
                <i class="fas fa-shopping-cart"></i>
                Order Logs
            </span>

            <?php if (!empty($search)): ?>
                <span class="result-text">
                    Result for:
                    "<?= htmlspecialchars($search) ?>"
                </span>
            <?php endif; ?>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="action-column">Action</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($res->num_rows > 0): ?>

                    <?php while ($o = $res->fetch_assoc()): ?>

                        <?php
                        $total = $o['price'] * $o['quantity'];

                        $imagePath = $o['image'];

                        if (!str_starts_with($imagePath, "images/")) {
                            $imagePath = "../images/" . $imagePath;
                        } else {
                            $imagePath = "../" . $imagePath;
                        }
                        ?>

                        <tr>

                            <td>
                                <span class="order-id">
                                    <?= htmlspecialchars($o['order_id']) ?>
                                </span>

                                <span class="sub-text">
                                    <?= date('d M Y', strtotime($o['created_at'])) ?>
                                </span>
                            </td>

                            <td>
                                <span class="client-name">
                                    <?= htmlspecialchars($o['user_name'] ?? 'Guest') ?>
                                </span>

                                <span class="sub-text">
                                    <?= htmlspecialchars($o['email']) ?>
                                </span>
                            </td>

                            <td>

                                <div class="product-info">

                                    <img
                                        src="<?= htmlspecialchars($imagePath) ?>"
                                        alt="<?= htmlspecialchars($o['product_name']) ?>"
                                        class="product-image"
                                        onerror="this.src='../images/no-image.png'"
                                    >

                                    <div>
                                        <div class="product-name">
                                            <?= htmlspecialchars($o['product_name']) ?>
                                        </div>

                                        <span class="sub-text">
                                            ₹<?= number_format($o['price'], 2) ?> each
                                        </span>
                                    </div>

                                </div>

                            </td>

                            <td>
                                <span class="quantity-badge">
                                    <?= (int) $o['quantity'] ?>
                                </span>
                            </td>

                            <td>
                                <span class="price">
                                    ₹<?= number_format($total, 2) ?>
                                </span>
                            </td>

                            <td>

                                <?php if ($o['status'] == 'Delivered'): ?>

                                    <span class="status-box delivered">
                                        Delivered
                                    </span>

                                <?php elseif ($o['status'] == 'Cancelled'): ?>

                                    <span class="status-box cancelled">
                                        Cancelled
                                    </span>

                                <?php else: ?>

                                    <span class="status-box pending">
                                        Pending
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="action-column">

                                <?php if ($o['status'] == 'Pending'): ?>

                                    <a
                                        href="?id=<?= $o['id'] ?>&status=Delivered"
                                        class="action-btn btn-ship"
                                    >
                                        <i class="fas fa-truck"></i>
                                        Ship
                                    </a>

                                <?php elseif ($o['status'] == 'Delivered'): ?>

                                    <a
                                        href="?id=<?= $o['id'] ?>&status=Pending"
                                        class="action-btn btn-undo"
                                    >
                                        Undo
                                    </a>

                                <?php else: ?>

                                    <span class="closed-text">
                                        CLOSED
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="empty-orders">
                            No orders found in this category.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <?php if ($total_pages > 1): ?>

        <div class="pagination">

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>

                <a
                    href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&filter=<?= urlencode($filter) ?>"
                    class="<?= ($page == $i) ? 'active' : '' ?>"
                >
                    <?= $i ?>
                </a>

            <?php endfor; ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>