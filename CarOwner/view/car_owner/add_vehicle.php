<?php
session_start();
require_once __DIR__ . "/../../controller/VehicleController.php";

$error = handleAddVehicle();

$page = "add_vehicle";
$page_title = "Add Vehicle - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>

<div class="page-header">
    <div class="welcome-msg">
        <h1>Add New Vehicle</h1>
        <p>Register a new car to your account.</p>
    </div>
    <button class="btn-secondary" onclick="location.href='dashboard.php'">Back to Dashboard</button>
</div>

<div class="content-area">
    <div class="form-container">
        <?php if ($error): ?>
            <div class="alert error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="brand">Brand</label>
                <input type="text" id="brand" name="brand" placeholder="e.g. Toyota" required>
            </div>

            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" placeholder="e.g. Camry" required>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" placeholder="e.g. 2020" min="1900" max="2100" required>
                </div>
                <div class="form-group half">
                    <label for="plate_number">Plate Number</label>
                    <input type="text" id="plate_number" name="plate_number" placeholder="e.g. ABC-123" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-main">Add Vehicle</button>
            </div>
        </form>
    </div>
</div>



<?php include_once __DIR__ . "/../layout/footer.php"; ?>