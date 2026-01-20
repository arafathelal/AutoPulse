<?php
require_once __DIR__ . "/../model/PartsModel.php";

/**
 * Handle View Orders
 */
function handleViewOrders()
{
    $userId = $_SESSION['user_id'] ?? 1; // Fallback
    $orders = getOrdersByUserId($userId);
    require __DIR__ . "/../view/car_owner/orders.php";
}

/**
 * Handle View Order Details
 */
function handleViewOrderDetails()
{
    $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $userId = $_SESSION['user_id'] ?? 1;

    if ($orderId > 0) {
        $order = getOrderById($orderId);

        // Security check: Ensure order belongs to logged-in user
        if ($order && $order['user_id'] == $userId) {
            require __DIR__ . "/../view/car_owner/order_details.php";
            return;
        }
    }

    // Redirect if invalid or unauthorized
    header("Location: orders.php");
    exit;
}
