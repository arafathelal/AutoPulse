<?php require_once 'header.php'; ?>

<!-- Page-specific CSS -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>CSS/products.css">

<h2 style="text-align:center; margin-top:30px;">All Products</h2>
<div id="products">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <?php if ($product['image_url']): ?>
                <img src="<?php echo BASE_URL . 'image/' . htmlspecialchars($product['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">

            <?php endif; ?>
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Price: ৳<?php echo $product['price']; ?></p>
            <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once 'footer.php'; ?>