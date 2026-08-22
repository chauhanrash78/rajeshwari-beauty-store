function loadCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    let container = document.getElementById("cartItems");
    let summary = document.getElementById("cartSummary");

    // Empty cart
    if (cart.length === 0) {
        summary.style.display = "none";

        container.style.display = "flex";
        container.style.justifyContent = "center";
        container.style.alignItems = "flex-start";
        container.style.height = "60vh";

        container.innerHTML = `
            <div class="empty-cart">
                <h2>Your Cart is Empty</h2>
                <button onclick="goHome()">Discover Products</button>
            </div>
        `;

        return;
    }

    summary.style.display = "block";

    container.style.display = "grid";
    container.style.gridTemplateColumns = "repeat(4, 1fr)";
    container.style.gap = "20px";

    fetch("getProducts.php")
        .then(res => res.json())
        .then(products => {
            let html = "";
            let grandTotal = 0;
            let cartCount = {};

            cart.forEach(id => {
                cartCount[id] = (cartCount[id] || 0) + 1;
            });

            Object.keys(cartCount).forEach(id => {
                let p = products.find(item => item.id == id);
                let qty = cartCount[id];

                if (p) {
                    let total = p.price * qty;
                    grandTotal += total;

                    html += `
                        <div class="card">
                            <img src="${p.image}" alt="${p.name}">
                            <h4>${p.name}</h4>
                            <p>₹${p.price}</p>

                            <div class="qty-container">
                                <button
                                    class="btn-minus"
                                    onclick="decreaseQty(${p.id})"
                                >
                                    −
                                </button>

                                <div class="qty-count">${qty}</div>

                                <button
                                    class="btn-plus"
                                    onclick="increaseQty(${p.id})"
                                >
                                    +
                                </button>
                            </div>

                            <p>
                                <strong>Total: ₹${total}</strong>
                            </p>

                            <button
                                class="remove-btn"
                                onclick="removeFromCart(${p.id})"
                            >
                                Remove
                            </button>
                        </div>
                    `;
                }
            });

            container.innerHTML = html;
            document.getElementById("grandTotal").innerText =
                "₹" + grandTotal;
        });
}

// Increase quantity
function increaseQty(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push(id);
    localStorage.setItem("cart", JSON.stringify(cart));

    loadCart();
}

// Decrease quantity
function decreaseQty(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let index = cart.indexOf(id);

    if (index > -1) {
        cart.splice(index, 1);
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
}

// Remove item
function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart = cart.filter(item => item != id);

    localStorage.setItem("cart", JSON.stringify(cart));
    loadCart();
}

// Checkout
function goToCheckout() {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    if (cart.length === 0) {
        alert("Cart is empty");
        return;
    }

    let buyNowData = cart.map(id => ({
        id: id,
        qty: 1
    }));

    localStorage.setItem(
        "buyNow",
        JSON.stringify(buyNowData)
    );

    window.location.href = "checkout.html";
}

// Home
function goHome() {
    window.location.href = "shop.php";
}

loadCart();