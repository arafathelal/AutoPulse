function addToCart(productId) {
    fetch('index.php?page=add-to-cart', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}&quantity=1`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
        } else if (data.status === 'redirect') {
            window.location.href = 'index.php?page=login';
        } else {
            alert(data.message);
        }
    });
}

function updateQuantity(productId, change) {
    const qtySpan = document.querySelector(`tr[data-product-id="${productId}"] .quantity`);
    let currentQty = parseInt(qtySpan.innerText);
    const newQty = currentQty + change;
    if (newQty < 1) return removeFromCart(productId);
    fetch('index.php?page=update-cart', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `product_id=${productId}&quantity=${newQty}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function removeFromCart(productId) {
    if (confirm('Remove item?')) {
        fetch('index.php?page=remove-from-cart', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}

function validateContact() {
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const message = document.querySelector('textarea[name="message"]').value;
    if (!name || !email || !message) {
        alert('All fields required');
        return false;
    }
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert('Invalid email');
        return false;
    }
    return true;
}

function validateLogin() {
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    if (!email || !password) {
        alert('All fields required');
        return false;
    }
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert('Invalid email');
        return false;
    }
    return true;
}

function validateRegister() {
    const name = document.querySelector('input[name="name"]').value;
    const email = document.querySelector('input[name="email"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const dob = document.querySelector('input[name="dob"]').value;
    const phone = document.querySelector('input[name="phone"]').value;
    const address = document.querySelector('textarea[name="address"]').value;
    if (!name || !email || !password || !dob || !phone || !address) {
        alert('All fields required');
        return false;
    }
    if (!/\S+@\S+\.\S+/.test(email)) {
        alert('Invalid email');
        return false;
    }
    if (password.length < 6) {
        alert('Password too short');
        return false;
    }
    return true;
}