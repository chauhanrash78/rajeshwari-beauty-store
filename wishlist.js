let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];

function loadWishlist() {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    let container = document.getElementById("wishlistItems");

    if (wishlist.length === 0) {
        container.style.display = "flex";
        container.style.justifyContent = "center";
        container.style.alignItems = "flex-start";
        container.style.paddingTop = "100px";

        container.innerHTML = `
        <div class="empty-cart">
            <h2>Your Wishlist is Empty</h2>
            <button onclick="goHome()">Discover Products</button>
        </div>`;

        return;
    }

    container.style.display = "grid";
    container.style.gridTemplateColumns = "repeat(4, 1fr)";
    container.style.gap = "20px";
    container.style.padding = "20px";

    fetch("getProducts.php")
        .then(res => res.json())
        .then(products => {
            let html = "";

            wishlist.forEach(id => {
                let p = products.find(item => item.id == id);

                if (p) {
                    html += `
                    <div class="card">
                        <img src="${p.image}" onclick="window.location.href='product.html?id=${p.id}'" style="cursor:pointer">
                        <h4>${p.name}</h4>
                        <p>₹${p.price}</p>

                        <button class="remove-wish-btn" onclick="removeFromWishlist(${p.id})">
                            Remove
                        </button>
                    </div>
                    `;
                }
            });

            container.innerHTML = html;
        });
}

// Remove item from wishlist
function removeFromWishlist(id) {
    let wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
    let updatedWishlist = wishlist.filter(item => item != id);

    localStorage.setItem("wishlist", JSON.stringify(updatedWishlist));
    loadWishlist();
}

function goHome() {
    window.location.href = "shop.php";
}

loadWishlist();