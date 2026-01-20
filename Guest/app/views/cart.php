<?php require_once 'header.php'; ?>
<h2>Your Cart</h2>

<?php if (isset($_SESSION['checkout_success'])): ?>
    <p><?php echo htmlspecialchars($_SESSION['checkout_success']); ?></p>
    <?php unset($_SESSION['checkout_success']); ?>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr data-product-id="<?php echo $item['product_id']; ?>">
                    <td>
                        <?php
                        // Use cart item's image_url with fallback
                        $img = !empty($item['image_url']) ? $item['image_url'] : 'default.jpg';
                        ?>
                        <img src="<?php echo BASE_URL . 'image/' . htmlspecialchars($img); ?>"
                            alt="<?php echo htmlspecialchars($item['name']); ?>" width="100">
                    </td>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>
                        <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, -1)">-</button>
                        <span class="quantity"><?php echo $item['quantity']; ?></span>
                        <button onclick="updateQuantity(<?php echo $item['product_id']; ?>, 1)">+</button>
                    </td>
                    <td>
                        ৳<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </td>
                    <td>
                        <button onclick="removeFromCart(<?php echo $item['product_id']; ?>)">Remove</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total: ৳<?php echo number_format($total, 2); ?></p>
    <a href="index.php?page=checkout"><button>Proceed to Checkout</button></a>
<?php endif; ?>

<?php require_once 'footer.php'; ?>