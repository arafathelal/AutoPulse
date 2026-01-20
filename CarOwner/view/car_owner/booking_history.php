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

require_once __DIR__ . "/../../controller/BookingController.php";

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

$page = "bookings";
$page_title = "My Bookings - AutoPulse";

// Handle Cancel Action if POST
handleCancelBooking();

// Get History
$bookings = getFullBookingHistory($user_id);

include_once __DIR__ . "/../layout/header.php";
?>

<div class="page-header">
    <div class="welcome-msg">
        <h1>Booking History</h1>
        <p>View and manage your service appointments and emergency requests.</p>
    </div>
    <button class="btn-main" onclick="location.href='book_home_service.php'">+ New Booking</button>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'cancelled'): ?>
    <div class="alert-success"
        style="background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        Booking cancelled successfully.
    </div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'failed_cancel'): ?>
    <div class="alert-danger"
        style="background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
        Failed to cancel booking. Please try again.
    </div>
<?php endif; ?>

<div class="content-area" style="grid-template-columns: 1fr;"> <!-- Full width -->
    <main class="activity-panel">
        <div class="panel-head">
            <h3>All Bookings</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Service Type</th>
                        <th>Vehicle</th>
                        <th>Date/Time</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">No booking history found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>
                                    <?php if (isset($booking['pickup_location'])): ?>
                                        <span class="badge badge-emergency"><i class="fa-solid fa-truck"></i> Emergency Tow</span>
                                    <?php else: ?>
                                        <span class="badge badge-service"><i class="fa-solid fa-wrench"></i> Home Service</span>
                                    <?php endif; ?>
                                    <div class="sub-text"><?= htmlspecialchars($booking['service_type']) ?></div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($booking['brand'] . ' ' . $booking['model']) ?>
                                    <div class="sub-text"><?= htmlspecialchars($booking['plate_number']) ?></div>
                                </td>
                                <td>
                                    <?php if (isset($booking['scheduled_date'])): ?>
                                        <div><i class="fa-regular fa-calendar"></i>
                                            <?= htmlspecialchars($booking['scheduled_date']) ?></div>
                                        <div class="sub-text"><i class="fa-regular fa-clock"></i>
                                            <?= htmlspecialchars($booking['scheduled_time']) ?></div>
                                    <?php else: ?>
                                        <div><i class="fa-regular fa-calendar"></i>
                                            <?= date('Y-m-d', strtotime($booking['created_at'])) ?></div>
                                        <div class="sub-text"><i class="fa-solid fa-bolt"></i>
                                            <?= $booking['is_immediate'] ? 'Immediate' : 'Scheduled' ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($booking['address'])): ?>
                                        <small><i class="fa-solid fa-map-pin"></i>
                                            <?= htmlspecialchars($booking['address']) ?></small>
                                    <?php elseif (isset($booking['pickup_location'])): ?>
                                        <small><i class="fa-solid fa-map-pin"></i>
                                            <?= htmlspecialchars($booking['pickup_location']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'status-pending';
                                    $status = $booking['status'];
                                    if ($status === 'Completed')
                                        $statusClass = 'status-completed';
                                    if ($status === 'Cancelled')
                                        $statusClass = 'status-cancelled';
                                    if ($status === 'In Progress')
                                        $statusClass = 'status-active';
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                                <td>
                                    <?php if ($booking['status'] === 'Pending'): ?>
                                        <form method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                            <input type="hidden" name="cancel_booking_id" value="<?= $booking['id'] ?>">
                                            <button type="submit" class="btn-cancel">Cancel</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>