<?php require_once __DIR__ . '/header.php'; ?>

<!-- Page-specific CSS for products -->
<link rel="stylesheet"
    href="<?php echo defined('BASE_URL') ? BASE_URL : '/WIP/AutoPulse/Guest/public/'; ?>CSS/products.css">

<h2>Welcome to AutoPulse</h2>
<p>Check out our discounted products!</p>
<?php if (isset($_SESSION['user_name'])): ?>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
<?php endif; ?>
<div id="products">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <?php if ($product['image_url']): ?>
                <img src="<?php echo BASE_URL . 'image/' . htmlspecialchars($product['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" width="200">

            <?php endif; ?>
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: ৳<?php echo $product['price']; ?></p>
            <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>