<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "../db.php";

$regular_threshold = 5;

// Total customers
$total_customers = $conn->query(
    "SELECT COUNT(*) as total FROM users"
)->fetch_assoc()['total'];

// Total orders
$total_orders_res = $conn->query(
    "SELECT COUNT(o.id) as total
     FROM orders o
     INNER JOIN users u ON o.email = u.email"
);

$total_orders = $total_orders_res->fetch_assoc()['total'];

// Total revenue
$rev_res = $conn->query(
    "SELECT SUM(o.quantity * p.price) as total
     FROM orders o
     INNER JOIN users u ON o.email = u.email
     JOIN products p ON o.product_id = p.id"
);

$total_revenue = $rev_res->fetch_assoc()['total'] ?? 0;

// Search and pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$start = ($page - 1) * $limit;

$search = isset($_GET['search'])
    ? trim($conn->real_escape_string($_GET['search']))
    : "";

$allowed_sort = ['name', 'total_orders', 'total_spent'];

$sort = isset($_GET['sort']) &&
        in_array($_GET['sort'], $allowed_sort)
    ? $_GET['sort']
    : 'name';

$order = isset($_GET['order']) &&
         strtoupper($_GET['order']) == 'DESC'
    ? 'DESC'
    : 'ASC';

// Pagination count
$count_sql = "SELECT COUNT(*) as total
              FROM users
              WHERE name LIKE '%$search%'
              OR email LIKE '%$search%'";

$total_filtered = $conn->query($count_sql)->fetch_assoc()['total'];
$total_pages = ceil($total_filtered / $limit);

// Fetch customers
$query = "
    SELECT
        u.id,
        u.name,
        u.email,
        (SELECT COUNT(*)
         FROM orders
         WHERE email = u.email) AS total_orders,
        (SELECT COALESCE(SUM(o.quantity * p.price), 0)
         FROM orders o
         JOIN products p ON o.product_id = p.id
         WHERE o.email = u.email) AS total_spent
    FROM users u
    WHERE u.name LIKE '%$search%'
       OR u.email LIKE '%$search%'
    GROUP BY u.id
    ORDER BY $sort $order
    LIMIT $start, $limit
";

$users = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Customer Insights</title>
    <link rel="stylesheet" href="customers.css">
</head>

<body>

<div class="header">🛒 Admin Customer Insights</div>

<div class="navbar">
    <div>
        <a href="../index.php">View Shop</a>
    </div>

    <div>
        <a href="index.php">Home</a>
        <a href="manage_products.php">Products</a>
        <a href="customers.php">Customers</a>
        <a href="orders.php">Orders</a>
        <a href="report.php">Report</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="summary-row">
    <div class="stat-box">
        <h3>Total Customers</h3>
        <p><?php echo $total_customers; ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Orders</h3>
        <p><?php echo $total_orders; ?></p>
    </div>

    <div class="stat-box">
        <h3>Total Revenue</h3>
        <p>₹<?php echo number_format($total_revenue, 0); ?></p>
    </div>
</div>

<div class="search-container">
    <form method="GET">
        <input
            type="text"
            name="search"
            placeholder="Search by Name or Email..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit" class="btn-pink">
            Search Now
        </button>

        <?php if ($search): ?>
            <a href="customers.php" class="clear-link">Clear</a>
        <?php endif; ?>
    </form>
</div>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>
                    <a href="?search=<?php echo $search; ?>&sort=name&order=<?php echo ($sort == 'name' && $order == 'ASC' ? 'desc' : 'asc'); ?>">
                        Customer Name ▲▼
                    </a>
                </th>

                <th>Email Address</th>

                <th>
                    <a href="?search=<?php echo $search; ?>&sort=total_orders&order=<?php echo ($sort == 'total_orders' && $order == 'ASC' ? 'desc' : 'asc'); ?>">
                        Orders
                    </a>
                </th>

                <th>
                    <a href="?search=<?php echo $search; ?>&sort=total_spent&order=<?php echo ($sort == 'total_spent' && $order == 'ASC' ? 'desc' : 'asc'); ?>">
                        Total Spent
                    </a>
                </th>

                <th>Recent Items Bought</th>
            </tr>
        </thead>

        <tbody>
            <?php
            if ($users->num_rows > 0) {
                while ($row = $users->fetch_assoc()) {
                    $email = $row['email'];

                    if ($row['total_orders'] >= 15) {
                        $badge = "<span class='badge badge-vip'>VIP Customer</span>";
                    } elseif ($row['total_orders'] >= $regular_threshold) {
                        $badge = "<span class='badge badge-regular'>Regular</span>";
                    } else {
                        $badge = "<span class='badge badge-new'>New User</span>";
                    }

                    $p_res = $conn->query(
                        "SELECT p.name
                         FROM orders o
                         JOIN products p ON o.product_id = p.id
                         WHERE o.email = '$email'
                         ORDER BY o.id DESC
                         LIMIT 2"
                    );

                    $items = [];

                    while ($p = $p_res->fetch_assoc()) {
                        $items[] = $p['name'];
                    }

                    $recent = !empty($items)
                        ? implode(", ", $items)
                        : "No orders";

                    echo "
                        <tr>
                            <td>
                                <strong>" . htmlspecialchars($row['name']) . "</strong>
                                <br>
                                $badge
                            </td>

                            <td class='email-cell'>
                                " . htmlspecialchars($row['email']) . "
                            </td>

                            <td class='orders-count'>
                                " . $row['total_orders'] . "
                            </td>

                            <td class='spent-amount'>
                                ₹" . number_format($row['total_spent'], 2) . "
                            </td>

                            <td class='recent-items'>
                                $recent
                            </td>
                        </tr>
                    ";
                }
            } else {
                echo "
                    <tr>
                        <td colspan='5' class='no-customers'>
                            No customers found.
                        </td>
                    </tr>
                ";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a
            href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
            class="<?php echo ($page == $i) ? 'active' : ''; ?>"
        >
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>