<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'CarOwner') {
    header("Location: /WIP/AutoPulse/Guest/public/index.php?page=login");
    exit();
}

require_once __DIR__ . "/../../controller/OrderController.php";

$userId = $_SESSION['user_id'];
$orders = getOrdersByUserId($userId);

$page = 'orders';
$page_title = 'My Orders - AutoPulse';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - AutoPulse</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/bookings.css"> <!-- Reuse bookings CSS for table layout -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .order-table th,
        .order-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        .order-table img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85em;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-delivered {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <?php include '../layout/header.php'; ?>

    <div class="main-content">
        <div class="container">
            <h1>My Parts Orders</h1>

            <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <p>You haven't placed any orders yet.</p>
                    <a href="buy_parts.php" class="btn btn-primary">Browse Parts</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="order-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Part</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <?php if ($order['image_url']): ?>
                                                <img src="<?php echo htmlspecialchars($order['image_url']); ?>" alt="Part">
                                            <?php else: ?>
                                                <div
                                                    style="width:50px; height:50px; background:#eee; display:flex; align-items:center; justify-content:center; border-radius:5px;">
                                                    <i class="fas fa-cogs"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span>
                                                <?php echo htmlspecialchars($order['part_name']); ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo $order['quantity']; ?>
                                    </td>
                                    <td>৳
                                        <?php echo number_format($order['total_price'], 2); ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">
                                            View Order
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>