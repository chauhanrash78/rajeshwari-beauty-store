<?php

session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include "../db.php";

$self = $_SERVER['PHP_SELF'];

// Add Product
if (isset($_POST['add'])) {
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];

    $tmp = $_FILES['image']['tmp_name'];
    $newName = time() . rand(1000, 9999) . ".jpg";

    if (!is_dir("../images")) {
        mkdir("../images", 0777, true);
    }

    move_uploaded_file($tmp, "../images/" . $newName);

    $imgPath = "images/" . $newName;

    $conn->query(
        "INSERT INTO products (brand, name, price, image)
         VALUES ('$brand', '$name', '$price', '$imgPath')"
    );

    header("Location: $self");
    exit();
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    $conn->query(
        "DELETE FROM products WHERE id=$id"
    );

    header("Location: $self");
    exit();
}

// Update Product
if (isset($_POST['update'])) {
    $id = (int) $_POST['id'];
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];

    if (!empty($_FILES['image']['name'])) {
        $tmp = $_FILES['image']['tmp_name'];
        $newName = time() . rand(1000, 9999) . ".jpg";

        move_uploaded_file(
            $tmp,
            "../images/" . $newName
        );

        $imgPath = "images/" . $newName;

        $conn->query(
            "UPDATE products
             SET brand='$brand',
                 name='$name',
                 price='$price',
                 image='$imgPath'
             WHERE id=$id"
        );
    } else {
        $conn->query(
            "UPDATE products
             SET brand='$brand',
                 name='$name',
                 price='$price'
             WHERE id=$id"
        );
    }

    header("Location: $self");
    exit();
}

$products = $conn->query(
    "SELECT * FROM products ORDER BY id DESC"
);

$total_count = $products->num_rows;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Products | Rajeshwari Beauty</title>

    <link
        rel="stylesheet"
        href="manage_products.css"
    >

</head>

<body>

<div class="header-section">

    <div class="header">

        <div class="admin-brand">

            <img
                src="../logo3.jpg"
                alt="Rajeshwari Beauty"
                class="admin-logo"
            >

            <span>
                Admin Product Management
            </span>

        </div>

    </div>

    <div class="navbar">

        <div class="nav-left">

            <a
                href="../index.php"
                target="_blank"
            >
                ← View Shop
            </a>

        </div>

        <div class="nav-right">

            <a href="index.php">
                Home
            </a>

            <a href="manage_products.php">
                Products
            </a>

            <a href="orders.php">
                Orders
            </a>

            <a href="customers.php">
                Customers
            </a>

            <a href="report.php">
                Report
            </a>

            <a
                href="logout.php"
                class="logout-btn"
            >
                Logout
            </a>

        </div>

    </div>

</div>

<div class="stat-container">

    <div class="total-box">
        Total Products:
        <?php echo $total_count; ?>
    </div>

</div>

<div class="search">

    <input
        type="text"
        id="searchInput"
        placeholder="Search by name or brand..."
    >

    <select id="priceFilter">

        <option value="">
            All Prices
        </option>

        <option value="0-100">
            Below 100
        </option>

        <option value="100-200">
            100 - 200
        </option>

        <option value="200-500">
            200 - 500
        </option>

        <option value="500-100000">
            500+
        </option>

    </select>

</div>

<div class="form-box">

    <h3>
        Add New Product
    </h3>

    <form
        method="POST"
        enctype="multipart/form-data"
    >

        <input
            type="text"
            name="brand"
            placeholder="Brand"
            required
        >

        <input
            type="text"
            name="name"
            placeholder="Product Name"
            required
        >

        <input
            type="number"
            name="price"
            placeholder="Price"
            required
        >

        <input
            type="file"
            name="image"
            required
        >

        <button
            type="submit"
            class="btn update"
            name="add"
        >
            Add Product
        </button>

    </form>

</div>

<div class="products">

    <?php while ($p = $products->fetch_assoc()): ?>

        <div
            class="card"
            data-name="<?php echo strtolower($p['name']); ?>"
            data-brand="<?php echo strtolower($p['brand']); ?>"
            data-price="<?php echo $p['price']; ?>"
        >

            <img
                src="../<?php echo $p['image']; ?>"
                alt="<?php echo htmlspecialchars($p['name']); ?>"
            >

            <p class="price">
                ₹<?php echo $p['price']; ?>
            </p>

            <p class="brand">
                <?php echo htmlspecialchars($p['brand']); ?>
            </p>

            <p class="name">
                <?php echo htmlspecialchars($p['name']); ?>
            </p>

            <button
                type="button"
                class="btn update"
                onclick="openModal(
                    <?php echo $p['id']; ?>,
                    '<?php echo addslashes($p['name']); ?>',
                    '<?php echo addslashes($p['brand']); ?>',
                    <?php echo $p['price']; ?>,
                    '<?php echo $p['image']; ?>'
                )"
            >
                Update
            </button>

            <a
                href="?delete=<?php echo $p['id']; ?>"
                class="btn delete"
                onclick="return confirm('Delete this product?');"
            >
                Delete
            </a>

        </div>

    <?php endwhile; ?>

</div>

<div
    class="modal"
    id="updateModal"
>

    <div class="modal-content">

        <button
            type="button"
            class="close"
            onclick="closeModal()"
        >
            ×
        </button>

        <h3>
            Update Product
        </h3>

        <form
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id"
                id="mid"
            >

            <input
                type="text"
                name="name"
                id="mname"
                placeholder="Name"
                required
            >

            <input
                type="text"
                name="brand"
                id="mbrand"
                placeholder="Brand"
                required
            >

            <input
                type="number"
                name="price"
                id="mprice"
                placeholder="Price"
                required
            >

            <p class="current-image-label">
                Current Image:
            </p>

            <img
                id="mimg"
                src=""
                alt="Current Product Image"
            >

            <input
                type="file"
                name="image"
            >

            <button
                type="submit"
                name="update"
                class="btn update modal-update-btn"
            >
                Update Product
            </button>

        </form>

    </div>

</div>

<script>

const searchInput =
    document.getElementById("searchInput");

const priceFilter =
    document.getElementById("priceFilter");

function filterProducts() {

    const search =
        searchInput.value
            .toLowerCase()
            .trim();

    const range =
        priceFilter.value;

    const cards =
        document.querySelectorAll(".card");

    cards.forEach(card => {

        const name =
            card.dataset.name;

        const brand =
            card.dataset.brand;

        const price =
            parseFloat(card.dataset.price);

        const matchSearch =
            name.includes(search) ||
            brand.includes(search);

        let matchPrice = true;

        if (range !== "") {

            const [min, max] =
                range.split("-").map(Number);

            matchPrice =
                price >= min &&
                price <= max;
        }

        card.style.display =
            matchSearch && matchPrice
                ? "block"
                : "none";
    });
}

searchInput.addEventListener(
    "keyup",
    filterProducts
);

priceFilter.addEventListener(
    "change",
    filterProducts
);

function openModal(
    id,
    name,
    brand,
    price,
    img
) {

    document.getElementById(
        "updateModal"
    ).style.display = "flex";

    document.getElementById("mid").value = id;
    document.getElementById("mname").value = name;
    document.getElementById("mbrand").value = brand;
    document.getElementById("mprice").value = price;
    document.getElementById("mimg").src = "../" + img;
}

function closeModal() {

    document.getElementById(
        "updateModal"
    ).style.display = "none";
}

window.addEventListener(
    "click",
    function(event) {

        const modal =
            document.getElementById("updateModal");

        if (event.target === modal) {
            closeModal();
        }
    }
);

</script>

</body>
</html>