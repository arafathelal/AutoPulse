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

require_once __DIR__ . "/../../controller/VehicleController.php";

$userId = $_SESSION['user_id'];

// Handle specific actions
handleDeleteVehicle();

$vehicles = getUserVehicles($userId);

$page = "vehicles";
$page_title = "My Vehicles - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>

<div class="page-header">
    <div class="welcome-msg">
        <h1>My Vehicles</h1>
        <p>Manage your registered vehicles.</p>
    </div>
    <button class="btn-main" onclick="location.href='add_vehicle.php'">+ Add New Vehicle</button>
</div>

<div class="content-area">
    <?php if (empty($vehicles)): ?>
        <div class="no-vehicles">
            <p>You haven't added any vehicles yet.</p>
            <br>
            <button class="btn-secondary" onclick="location.href='add_vehicle.php'">Add Your First Vehicle</button>
        </div>
    <?php else: ?>
        <div class="vehicle-grid">
            <?php foreach ($vehicles as $vehicle): ?>
                <div class="vehicle-card">
                    <div class="vehicle-header">
                        <span class="vehicle-brand">
                            <?= htmlspecialchars($vehicle['brand']) ?>
                            <?= htmlspecialchars($vehicle['model']) ?>
                        </span>
                        <span class="vehicle-plate">
                            <?= htmlspecialchars($vehicle['plate_number']) ?>
                        </span>
                    </div>
                    <div class="vehicle-details">
                        <p><strong>Year:</strong>
                            <?= htmlspecialchars($vehicle['year']) ?>
                        </p>
                        <!-- Add more details here if available in DB -->
                    </div>
                    <div class="vehicle-actions" style="margin-top: auto; text-align: right;">
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                            <button type="submit" class="btn-secondary" style="background-color: #e74c3c;"
                                onclick="return confirm('Are you sure you want to delete this vehicle?');">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>