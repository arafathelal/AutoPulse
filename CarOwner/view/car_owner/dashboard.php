<?php
session_start();


header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'CarOwner') {

    header("Location: /WIP/AutoPulse/Guest/public/index.php?page=login");
    exit();
}

require_once __DIR__ . "/../../controller/DashboardController.php";

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

$page = "dashboard";
$page_title = "Dashboard - AutoPulse";


$data = getDashboardData($user_id);

$total_vehicles = $data['stats']['total_vehicles'];
$active_services = $data['stats']['active_services'];
$parts_orders = $data['stats']['parts_orders'];
$total_spent = $data['stats']['total_spent'];
$recent_activities = $data['recent'];

include_once __DIR__ . "/../layout/header.php";
?>

<div class="page-header">
    <div class="welcome-msg">
        <h1>Welcome back, <?= htmlspecialchars($user_name) ?>!</h1>
        <p>Here is an overview of your vehicles and service status.</p>
    </div>
    <button class="btn-main" onclick="location.href='book_home_service.php'">+ New Booking</button>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-info"><span>My Vehicles</span>
            <h3><?= $total_vehicles ?></h3>
        </div><i class="fa-solid fa-car stat-icon"></i>
    </div>
    <div class="stat-card green">
        <div class="stat-info"><span>Active Services</span>
            <h3><?= $active_services ?></h3>
        </div><i class="fa-solid fa-wrench stat-icon"></i>
    </div>
    <div class="stat-card orange">
        <div class="stat-info"><span>Parts Orders</span>
            <h3><?= $parts_orders ?></h3>
        </div><i class="fa-solid fa-box-open stat-icon"></i>
    </div>
    <div class="stat-card red">
        <div class="stat-info"><span>Total Spent</span>
            <h3><?= $total_spent ?></h3>
        </div><i class="fa-solid fa-receipt stat-icon"></i>
    </div>
</div>

<div class="content-area">
    <aside>
        <div class="widget-box">
            <div class="widget-title">Quick Actions</div>
            <button class="action-link" onclick="location.href='add_vehicle.php'"><i class="fa-solid fa-car"></i> Add
                Vehicle</button>
            <button class="action-link" onclick="location.href='book_home_service.php'"><i
                    class="fa-solid fa-house"></i> Book Home Service</button>

            <button class="action-link btn-emergency" onclick="location.href='book_emergency.php'"><i
                    class="fa-solid fa-truck"></i> Emergency Tow</button>
            <button class="action-link" onclick="location.href='buy_parts.php'"><i class="fa-solid fa-gear"></i> Buy
                Parts</button>
        </div>

        <div class="help-widget">
            <div class="help-header"><i class="fa-solid fa-circle-info"></i> Need Help?</div>
            <p class="help-body">Contact support for issues with your bookings or orders.</p>
            <a href="support.php" class="help-link">Visit Support Center →</a>
        </div>
    </aside>

    <main class="activity-panel">
        <div class="panel-head">
            <h3>Recent Activity</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_activities)): ?>
                        <tr>
                            <td colspan="4" class="empty-state">No recent activity found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <tr>
                                <td>
                                    <?php if ($activity['type'] == 'Service'): ?>
                                        <span class="badge badge-booking">Service</span>
                                    <?php else: ?>
                                        <span class="badge badge-order">Part Order</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($activity['description']) ?></td>
                                <td><?= date('M d, Y', strtotime($activity['date'])) ?></td>
                                <td>
                                    <span class="status-badge <?= strtolower($activity['status']) ?>">
                                        <?= htmlspecialchars($activity['status']) ?>
                                    </span>
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