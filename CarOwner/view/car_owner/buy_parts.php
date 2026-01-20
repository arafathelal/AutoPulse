<?php
session_start();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'CarOwner') {
    header("Location: /WIP/AutoPulse/Guest/public/index.php?page=login");
    exit();
}

require_once __DIR__ . "/../../controller/PartsController.php";

$userId = $_SESSION['user_id'];


$parts = getPartsForView();
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

$page = "buy_parts";
$page_title = "Buy Parts - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/buy_parts.css">

<div class="page-header">
    <div class="welcome-msg">
        <h1>Buy Auto Parts</h1>
        <p>High quality parts delivered to your door.</p>
    </div>
    <div class="header-actions">
        <a href="cart.php" class="btn-secondary cart-btn">
            <i class="fa-solid fa-cart-shopping"></i> Cart (<span id="cartCount"><?= $cartCount ?></span>)
        </a>
        <button class="btn-secondary" onclick="location.href='dashboard.php'">Back to Dashboard</button>
    </div>
</div>

<div class="content-area">
    <div class="parts-grid">
        <?php foreach ($parts as $part): ?>
            <div class="part-card">
                <div class="part-image">
                    <?php if (!empty($part['image_url'])): ?>
                        <img src="<?= htmlspecialchars($part['image_url']) ?>" alt="<?= htmlspecialchars($part['name']) ?>">
                    <?php else: ?>
                        <i class="fa-solid fa-gears fa-3x"></i>
                    <?php endif; ?>
                </div>
                <div class="part-details">
                    <h3><?= htmlspecialchars($part['name']) ?></h3>
                    <p><?= htmlspecialchars($part['description']) ?></p>
                    <div class="part-meta">
                        <span class="price">৳<?= number_format($part['price'], 2) ?></span>
                        <?php if ($part['stock'] > 0): ?>
                            <span class="stock in-stock">In Stock (<?= $part['stock'] ?>)</span>
                        <?php else: ?>
                            <span class="stock out-of-stock">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="part-actions">
                    <?php if ($part['stock'] > 0): ?>
                        <div class="qty-input">
                            <input type="number" id="qty_<?= $part['id'] ?>" value="1" min="1" max="<?= $part['stock'] ?>">
                        </div>
                        <button class="btn-cart" onclick="addToCart(<?= $part['id'] ?>)">Add to Cart</button>
                        <button class="btn-main" onclick="buyNow(<?= $part['id'] ?>)">Buy Now</button>
                    <?php else: ?>
                        <button class="btn-secondary" disabled>Unavailable</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="toast" class="toast hidden">Item added to cart!</div>

<script>
    function addToCart(partId) {
        const qty = document.getElementById('qty_' + partId).value;
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('part_id', partId);
        formData.append('quantity', qty);

        fetch('ajax_cart.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message);
                    document.getElementById('cartCount').textContent = data.cart_count;
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function buyNow(partId) {
        const qty = document.getElementById('qty_' + partId).value;
        window.location.href = `checkout.php?action=buynow&id=${partId}&qty=${qty}`;
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            toast.classList.add('hidden');
        }, 3000);
    }
</script>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>