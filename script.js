let allProducts = [];
let selectedCategory = "";

// Load products
fetch("getProducts.php")
    .then(res => res.json())
    .then(data => {
        allProducts = data;
        displayProducts(data);
    })
    .catch(err => console.log(err));

// Display products
function displayProducts(data) {
    let html = "";

    data.forEach(p => {
        html += `
            <div class="card">
                <img src="${p.image}" alt="${p.name}">

                <h5>${p.brand}</h5>
                <small>${p.category} | ${p.subcategory}</small>

                <h4>${p.name}</h4>

                <p>&#8377;${p.price}</p>

                <div class="overlay">
                    <button onclick="addToCart(${p.id})">Cart 🛒</button>
                    <button onclick="addToWishlist(${p.id})">Like ❤️</button>
                    <button onclick="openProduct(${p.id})">View 👁️</button>
                </div>
            </div>
        `;
    });

    document.getElementById("products").innerHTML = html;
}

// Filter products
document.querySelectorAll(".sidebar input").forEach(cb => {
    cb.addEventListener("change", filterData);
});

function filterData() {
    let selectedBrands = [...document.querySelectorAll(".brand:checked")]
        .map(cb => cb.value.toLowerCase().trim());

    let selectedSub = [...document.querySelectorAll(".sub:checked")]
        .map(cb => cb.value.toLowerCase().trim());

    let filtered = allProducts.filter(p => {
        let brand = (p.brand || "").toLowerCase().trim();
        let sub = (p.subcategory || "").toLowerCase().trim();
        let cat = (p.category || "").toLowerCase().trim();

        let brandMatch =
            selectedBrands.length === 0 || selectedBrands.includes(brand);

        let subMatch =
            selectedSub.length === 0 || selectedSub.includes(sub);

        let catMatch =
            !selectedCategory || cat === selectedCategory;

        return brandMatch && subMatch && catMatch;
    });

    displayProducts(filtered);
}

// Open product
function openProduct(id) {
    window.location.href = "product.html?id=" + id;
}

// Cart
function addToCart(id) {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push(id);
    localStorage.setItem("cart", JSON.stringify(cart));

    updateCartCount();

    alert("Added to Cart 🛒");
}

// Wishlist
function addToWishlist(id) {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

    wishlist.push(id);
    localStorage.setItem("wishlist", JSON.stringify(wishlist));

    alert("Added to Wishlist ❤️");
}

function openWishlist() {
    window.location.href = "wishlist.html";
}

// Cart count
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    document.getElementById("cartCount").innerText = cart.length;
}

updateCartCount();

// Category filter
function filterCategory(cat) {
    selectedCategory = cat.toLowerCase();
    filterData();
}

// Logout
function logout() {
    localStorage.removeItem("user");

    alert("Logged out successfully");

    window.location.href = "login.html";
}

// Payment method
function selectPayment(method) {
    document.querySelector(`input[value="${method}"]`).checked = true;

    let html = "";

    if (method === "UPI") {
        html = `
            <input type="text" placeholder="Enter UPI ID (example@upi)">
        `;
    }

    if (method === "Card") {
        html = `
            <input type="text" placeholder="Card Number"><br>
            <input type="text" placeholder="Expiry Date"><br>
            <input type="text" placeholder="CVV">
        `;
    }

    if (method === "COD") {
        html = `<p>Pay cash when product is delivered.</p>`;
    }

    document.getElementById("paymentDetails").innerHTML = html;
}

// Search products
function searchProducts() {
    let searchValue = document
        .getElementById("searchInput")
        .value
        .toLowerCase()
        .trim();

    let filtered = allProducts.filter(p => {
        let name = (p.name || "").toLowerCase();
        let brand = (p.brand || "").toLowerCase();
        let category = (p.category || "").toLowerCase();

        return (
            name.includes(searchValue) ||
            brand.includes(searchValue) ||
            category.includes(searchValue)
        );
    });

    displayProducts(filtered);
}

// Place order
function placeOrder() {
    let name = document.getElementById("name").value.trim();
    let address = document.getElementById("address").value.trim();
    let phone = document.getElementById("phone").value.trim();

    let payment = document.querySelector(
        'input[name="payment"]:checked'
    ).value;

    if (name === "" || address === "" || phone === "") {
        alert("Please fill all fields");
        return;
    }

    if (!/^[0-9]{10}$/.test(phone)) {
        alert("Enter valid phone number");
        return;
    }

    fetch("placeOrder.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            email: user,
            orders: orders,
            address: address,
            payment: payment
        })
    })
    .then(res => res.text())
    .then(data => {
        alert("Order placed successfully");

        localStorage.removeItem("buyNow");
        localStorage.removeItem("cart");

        window.location.href = "success.html";
    });
}

// Get status color
function getColor(status) {
    if (status === "Pending") return "orange";
    if (status === "Shipped") return "blue";
    if (status === "Delivered") return "green";
    if (status === "Cancelled") return "red";
}