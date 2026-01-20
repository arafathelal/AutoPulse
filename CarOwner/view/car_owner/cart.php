<?php
session_start();
require_once __DIR__ . "/../../controller/PartsController.php";

$userId = $_SESSION['user_id'] ?? 1;

// Handle remove action
if (isset($_GET['remove'])) {
    removeFromCart(intval($_GET['remove']));
    header("Location: cart.php");
    exit;
}

$cartData = getCartItems();
$cartItems = $cartData['items'];
$totalPrice = $cartData['total'];

$page = "cart";
$page_title = "My Cart - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/buy_parts.css">

<div class="page-header">
    <div class="welcome-msg">
        <h1>My Shopping Cart</h1>
    </div>
    <button class="btn-secondary" onclick="location.href='buy_parts.php'">Continue Shopping</button>
</div>

<div class="content-area">
    <div class="cart-container">
        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <i class="fa-solid fa-cart-shopping fa-3x"></i>
                <p>Your cart is empty.</p>
                <a href="buy_parts.php" class="btn-main">Browse Parts</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <div class="cart-item-info">
                                        <?php if (!empty($item['part']['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($item['part']['image_url']) ?>" alt="img">
                                        <?php endif; ?>
                                        <span>
                                            <?= htmlspecialchars($item['part']['name']) ?>
                                        </span>
                                    </div>
                                </td>
                                <td>৳
                                    <?= number_format($item['part']['price'], 2) ?>
                                </td>
                                <td>
                                    <?= $item['qty'] ?>
                                </td>
                                <td>৳
                                    <?= number_format($item['subtotal'], 2) ?>
                                </td>
                                <td>
                                    <a href="?remove=<?= $item['part']['id'] ?>" class="btn-danger btn-sm"><i
                                            class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td colspan="2" class="total-price">৳
                                <?= number_format($totalPrice, 2) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="cart-actions">
                <button class="btn-main" onclick="location.href='checkout.php'">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>