let productId = new URLSearchParams(window.location.search).get("id");
let user = localStorage.getItem("user");

// Load product details
fetch("getSingleProduct.php?id=" + productId)
    .then(res => res.json())
    .then(p => {
        if (p.error) {
            alert("Product not found!");
            window.location.href = "index.html";
            return;
        }

        document.getElementById("productImg").src = p.image;
        document.getElementById("productName").innerText = p.name;
        document.getElementById("productBrand").innerText = "Brand: " + p.brand;
        document.getElementById("productCategory").innerText =
            p.category + " | " + p.subcategory;
        document.getElementById("productPrice").innerText = "₹" + p.price;

        loadRelated(p.category, p.subcategory);
    });

// Load related products
function loadRelated(category, subcategory) {
    fetch("getProducts.php")
        .then(res => res.json())
        .then(data => {
            let html = "";

            let related = data.filter(
                p =>
                    p.id != productId &&
                    (p.subcategory === subcategory || p.category === category)
            );

            related.sort(
                (a, b) =>
                    (b.subcategory === subcategory) -
                    (a.subcategory === subcategory)
            );

            related.forEach(p => {
                let imgPath = p.image;

                if (!imgPath.startsWith("images/")) {
                    imgPath = "images/" + imgPath;
                }

                html += `
                    <div class="card" style="min-width: 250px; flex-shrink: 0;">
                        <img
                            src="${imgPath}"
                            alt="${p.name}"
                            onerror="this.src='images/no-image.png'"
                        >

                        <p style="font-size: 12px; color: #333; margin: 5px 0 0 0;">
                            ${p.brand}
                        </p>

                        <p style="font-size: 11px; color: #666;">
                            ${p.category} | ${p.subcategory}
                        </p>

                        <h4 style="margin: 5px 0;">${p.name}</h4>
                        <p>₹${p.price}</p>

                        <div class="overlay">
                            <button onclick="event.stopPropagation(); addToCart('${p.id}')">
                                Cart 🛒
                            </button>

                            <button onclick="event.stopPropagation(); addToWishlist('${p.id}')">
                                Like ❤️
                            </button>

                            <button onclick="event.stopPropagation(); window.location.href='product.html?id=${p.id}'">
                                View 👁️
                            </button>
                        </div>
                    </div>
                `;
            });

            const relatedDiv = document.getElementById("relatedProducts");

            relatedDiv.innerHTML = html;
            relatedDiv.style.display = "flex";
            relatedDiv.style.overflowX = "auto";
            relatedDiv.style.gap = "20px";
            relatedDiv.style.padding = "20px";
            relatedDiv.style.whiteSpace = "nowrap";
            relatedDiv.style.scrollbarWidth = "thin";
        });
}

// Add related product to cart
function addToCart(id) {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push(id.toString());
    localStorage.setItem("cart", JSON.stringify(cart));

    updateCartCount();
    alert("Added to Cart 🛒");
}

// Add current product to cart
function addToCartProduct() {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    cart.push(productId);
    localStorage.setItem("cart", JSON.stringify(cart));

    updateCartCount();
    alert("Added to Cart 🛒");
}

// Add product to wishlist
function addToWishlist(id) {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

    if (!wishlist.includes(id.toString())) {
        wishlist.push(id.toString());
        localStorage.setItem("wishlist", JSON.stringify(wishlist));
        alert("Added to Wishlist");
    } else {
        alert("Already in Wishlist ❤️");
    }
}

// Buy current product
function buyNow() {
    let user = localStorage.getItem("user");

    if (!user) {
        alert("Please login first");
        window.location.href = "login.html";
        return;
    }

    let qty = document.getElementById("qty").value;
    let order = {
        id: productId,
        qty: qty
    };

    localStorage.setItem("buyNow", JSON.stringify(order));
    window.location.href = "checkout.html";
}

// Update cart count
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let countSpan = document.getElementById("cartCount");

    if (countSpan) {
        countSpan.innerText = cart.length;
    }
}

updateCartCount();