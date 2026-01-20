<?php
session_start();
require_once __DIR__ . "/../../controller/PartsController.php";

$userId = $_SESSION['user_id'] ?? 1;

$checkoutItems = [];
$totalPrice = 0;

$action = $_GET['action'] ?? 'cart';

// Handle "Buy Now" - Single Item
if ($action === 'buynow') {
    $partId = intval($_GET['id'] ?? 0);
    $qty = intval($_GET['qty'] ?? 1);
    $part = getPartById($partId);
    if ($part) {
        $subtotal = $part['price'] * $qty;
        $checkoutItems[] = [
            'part' => $part,
            'qty' => $qty,
            'subtotal' => $subtotal
        ];
        $totalPrice = $subtotal;
    }
} else {
    // Handle "Checkout from Cart"
    $cartData = getCartItems();
    $checkoutItems = $cartData['items'];
    $totalPrice = $cartData['total'];
}

// Handle Form Submission
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = trim($_POST['address'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    // We need to know if we are processing cart or single item.
    // Ideally, we should reconstruct items from hidden fields or session for security.
    // If "Buy Now", we are trusting the GET params re-passed or hidden inputs.
    // Let's use hidden inputs for simplicity in this prototype.

    $processedItems = [];
    if (isset($_POST['is_buynow']) && $_POST['is_buynow'] == '1') {
        $pId = intval($_POST['part_id']);
        $pQty = intval($_POST['quantity']);
        $pPart = getPartById($pId);
        if ($pPart) {
            $processedItems[] = [
                'part' => $pPart,
                'qty' => $pQty,
                'subtotal' => $pPart['price'] * $pQty
            ];
        }
    } else {
        // Process from Session Cart
        $cartData = getCartItems();
        $processedItems = $cartData['items'];
    }

    if (empty($address) || empty($contact)) {
        $errors[] = "Address and Contact are required.";
    } elseif (empty($processedItems)) {
        $errors[] = "No items to checkout.";
    } else {
        $result = processCheckout($userId, $address, $contact, $processedItems);
        if ($result['success']) {
            $success = true;
            $checkoutItems = []; // Clear display
            $totalPrice = 0;
        } else {
            $errors = $result['errors'];
        }
    }
}

$page = "checkout";
$page_title = "Checkout - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/buy_parts.css">

<div class="page-header">
    <div class="welcome-msg">
        <h1>Checkout</h1>
        <p>Review your order and confirm delivery details.</p>
    </div>
    <button class="btn-secondary" onclick="location.href='buy_parts.php'">Back to Shop</button>
</div>

<div class="content-area">
    <div class="checkout-container">

        <?php if ($success): ?>
            <div class="alert success">
                <h2>Order Placed Successfully! <i class="fa-solid fa-check-circle"></i></h2>
                <p>Your order has been confirmed. You can track it in your dashboard.</p>
                <button class="btn-main" onclick="location.href='dashboard.php'">Go to Dashboard</button>
            </div>
        <?php elseif (empty($checkoutItems)): ?>
            <div class="alert warning">
                <p>Your cart is empty or the item is invalid.</p>
                <a href="buy_parts.php">Go back to shop</a>
            </div>
        <?php else: ?>

            <?php if (!empty($errors)): ?>
                <div class="alert error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($checkoutItems as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['part']['name']) ?></td>
                                    <td>৳<?= number_format($item['part']['price'], 2) ?></td>
                                    <td><?= $item['qty'] ?></td>
                                    <td>৳<?= number_format($item['subtotal'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="total-row">
                                <td colspan="3">Total</td>
                                <td>৳<?= number_format($totalPrice, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="checkout-form">
                <h3>Delivery Details</h3>
                <form method="POST" action="">
                    <?php if ($action === 'buynow'): ?>
                        <input type="hidden" name="is_buynow" value="1">
                        <input type="hidden" name="part_id" value="<?= $checkoutItems[0]['part']['id'] ?>">
                        <input type="hidden" name="quantity" value="<?= $checkoutItems[0]['qty'] ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="address">Delivery Address *</label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter full delivery address..."
                            required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact Number *</label>
                        <input type="text" id="contact" name="contact" placeholder="e.g. 01712345678" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-main">Confirm Order</button>
                    </div>
                </form>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>