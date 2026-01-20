<?php
session_start();
require_once __DIR__ . "/../../controller/OrderController.php";

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$orderId = intval($_GET['id']);
$userId = $_SESSION['user_id'] ?? 1; // Fallback
$order = getOrderById($orderId);

// Security check
if (!$order || $order['user_id'] != $userId) {
    header("Location: orders.php");
    exit;
}

$page = 'orders';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - AutoPulse</title>
    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        .invoice-box {
            max-width: 800px;
            /* Default for screen */
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
            background: #fff;
        }

        /* Force smaller width for better PDF fit if needed, though html2pdf handles scaling */
        @media print {
            .invoice-box {
                max-width: 100%;
                border: none;
                box-shadow: none;
            }
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .action-buttons {
            max-width: 800px;
            margin: 20px auto;
            text-align: right;
        }
    </style>
</head>

<body>

    <?php include '../layout/header.php'; ?>

    <div class="main-content" style="padding-top: 100px;">

        <div class="action-buttons">
            <a href="orders.php" class="btn btn-secondary">Back to Orders</a>
            <button onclick="downloadInvoice()" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Download
                Invoice</button>
        </div>

        <div id="invoice" class="invoice-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td class="title">
                                    AutoPulse
                                </td>

                                <td>
                                    Invoice #:
                                    <?php echo $order['id']; ?><br>
                                    Created:
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?><br>
                                    Status:
                                    <?php echo $order['status']; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="information">
                    <td colspan="2">
                        <table>
                            <tr>
                                <td>
                                    AutoPulse Inc.<br>
                                    1234 Car Lane<br>
                                    Automobile City, AC 12345
                                </td>

                                <td>
                                    <?php
                                    // Simple user info simulation
                                    echo "User ID: " . $order['user_id'] . "<br>";
                                    echo htmlspecialchars($order['delivery_address']) . "<br>";
                                    echo htmlspecialchars($order['contact_number']);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="heading">
                    <td>
                        Payment Method
                    </td>

                    <td>
                        Check #
                    </td>
                </tr>

                <tr class="details">
                    <td>
                        Cash On Delivery
                    </td>

                    <td>
                        -
                    </td>
                </tr>

                <tr class="heading">
                    <td>
                        Item
                    </td>

                    <td>
                        Price
                    </td>
                </tr>

                <tr class="item">
                    <td>
                        <?php echo htmlspecialchars($order['part_name']); ?> (x
                        <?php echo $order['quantity']; ?>)
                    </td>

                    <td>
                        ৳
                        <?php echo number_format($order['total_price'], 2); ?>
                    </td>
                </tr>

                <tr class="total">
                    <td></td>

                    <td>
                        Total: ৳
                        <?php echo number_format($order['total_price'], 2); ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script>
        function downloadInvoice() {
            const element = document.getElementById('invoice');
            var opt = {
                margin: [10, 10, 10, 10], // top, left, bottom, right
                filename: 'invoice_<?php echo $order['id']; ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
            };

            // Temporary reduce width for PDF generation to fit A4
            var originalWidth = element.style.maxWidth;
            element.style.maxWidth = '700px';

            html2pdf().set(opt).from(element).save().then(function () {
                element.style.maxWidth = originalWidth; // Restore width
            });
        }
    </script>
</body>

</html>