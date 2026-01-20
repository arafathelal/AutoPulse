<?php
session_start();
require_once __DIR__ . "/../../controller/HomeServiceController.php";

$userId = $_SESSION['user_id'] ?? 1;
$result = handleBookService();
$errors = $result['errors'];
$success = $result['success'];


$formData = getBookingFormData($userId);
$vehicles = $formData['vehicles'];
$services_list = $formData['services'];

$page = "book_home_service";
$page_title = "Book Home Service - AutoPulse";

include_once __DIR__ . "/../layout/header.php";
?>

<div class="page-header">
    <div class="welcome-msg">
        <h1>Book Home Service</h1>
        <p>Schedule a professional service at your doorstep.</p>
    </div>
    <button class="btn-secondary" onclick="location.href='dashboard.php'">Back to Dashboard</button>
</div>

<div class="content-area">
    <div class="form-container">
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

        <form method="POST" action="" id="bookingForm" onsubmit="return validateBookingForm()">

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
                <label for="service_type">Service Type *</label>
                <select id="service_type" name="service_type" required>
                    <option value="">-- Choose Service --</option>

                    <?php if (isset($services_list)): ?>
                        <?php foreach ($services_list as $s): ?>
                            <option value="<?= htmlspecialchars($s['name']) ?>">
                                <?= htmlspecialchars($s['name']) ?> - ৳<?= htmlspecialchars($s['price']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group half">
                    <label for="date">Preferred Date *</label>
                    <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group half">
                    <label for="time">Preferred Time *</label>
                    <input type="time" id="time" name="time" required>
                </div>
            </div>

            <div class="form-group">
                <label for="address">Service Address *</label>
                <textarea id="address" name="address" rows="2" placeholder="Enter full address for the service..."
                    required></textarea>
            </div>

            <div class="form-group">
                <label for="contact">Contact Number *</label>
                <input type="text" id="contact" name="contact" placeholder="e.g. 01712345678" required>
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Any specific instructions?"></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-main">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/book_home_service.js"></script>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>