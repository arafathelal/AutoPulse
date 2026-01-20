<?php
session_start();
require_once __DIR__ . "/../../controller/EmergencyController.php";

$userId = $_SESSION['user_id'] ?? 1;
$result = handleEmergencyTow();
$errors = $result['errors'];
$success = $result['success'];

// Get vehicles for dropdown
$vehicles = getTowFormData($userId);

$page = "book_emergency";
$page_title = "Emergency Tow - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/book_home_service.css">
<style>
    .emergency-header {
        background: linear-gradient(135deg, #d32f2f, #b71c1c) !important;
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
    }

    .emergency-header h1 {
        color: white !important;
        margin-bottom: 0.5rem;
    }

    .emergency-header p {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .emergency-alert {
        background-color: #ffebee;
        color: #c62828;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #ef9a9a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-emergency-submit {
        background: #d32f2f !important;
    }

    .btn-emergency-submit:hover {
        background: #b71c1c !important;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-group input {
        width: auto;
        margin: 0;
    }

    .checkbox-group label {
        margin: 0;
        display: inline-block;
        font-weight: normal;
        cursor: pointer;
    }
</style>

<div class="page-header emergency-header">
    <div class="welcome-msg">
        <h1><i class="fa-solid fa-truck-medical"></i> Emergency Tow Service</h1>
        <p>Request immediate assistance. We will tow your vehicle to our nearest repair center.</p>
    </div>
    <button class="btn-secondary" onclick="location.href='dashboard.php'"
        style="background: rgba(255,255,255,0.2); color: white; border: none;">Back to Dashboard</button>
</div>

<div class="content-area">
    <div class="form-container">
        <div class="emergency-alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>
                <strong>Note:</strong> Your vehicle will be towed to the AutoPulse authorized repair shop.
            </div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="" id="towForm" onsubmit="return validateTowForm()">

            <div class="form-group">
                <label for="vehicle_id">Select Vehicle *</label>
                <select id="vehicle_id" name="vehicle_id" required>
                    <option value="">-- Choose a Vehicle --</option>
                    <?php foreach ($vehicles as $v): ?>
                        <option value="<?= $v['id'] ?>">
                            <?= htmlspecialchars($v['brand'] . ' ' . $v['model'] . ' (' . $v['plate_number'] . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($vehicles)): ?>
                    <small class="hint-text">No vehicles found. <a href="add_vehicle.php">Add a vehicle first.</a></small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="pickup_location">Pickup Location (Current Location) *</label>
                <textarea id="pickup_location" name="pickup_location" rows="2"
                    placeholder="Where is the vehicle currently located?" required></textarea>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number *</label>
                <input type="text" id="contact" name="contact" placeholder="e.g. 01712345678" required>
            </div>

            <div class="form-group">
                <label for="description">Problem Description</label>
                <textarea id="description" name="description" rows="3"
                    placeholder="Briefly describe what happened..."></textarea>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" id="is_immediate" name="is_immediate" value="1" checked>
                <label for="is_immediate">I need immediate assistance</label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-main btn-emergency-submit">Request Tow</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/book_emergency.js"></script>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>