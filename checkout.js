let user = localStorage.getItem("user");

if (!user) {
    alert("Login first");
    window.location.href = "login.html";
}

// Get selected products
let raw = localStorage.getItem("buyNow");

if (!raw) {
    alert("No product selected");
    window.location.href = "cart.html";
}

let orders = JSON.parse(raw);

if (!Array.isArray(orders)) {
    orders = [orders];
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

    if (!/^[A-Za-z ]+$/.test(name)) {
        alert("Name should contain only letters");
        return;
    }

    if (address.length < 5) {
        alert("Enter valid address");
        return;
    }

    if (!/^[0-9]{10}$/.test(phone)) {
        alert("Enter valid 10-digit phone number");
        return;
    }

    // Send order details
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
        alert("Order Placed Successfully ✅");

        localStorage.removeItem("cart");
        localStorage.removeItem("buyNow");

        window.location.href = "success.html";
    });
}