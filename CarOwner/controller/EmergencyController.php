<?php
require_once __DIR__ . "/../model/EmergencyModel.php";
require_once __DIR__ . "/../model/VehicleModel.php";

/**
 * Handle the Emergency Tow form submission
 */
function handleEmergencyTow()
{
    global $conn;
    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'] ?? 1; // Fallback for dev

        $vehicleId = intval($_POST['vehicle_id'] ?? 0);
        $pickup = trim($_POST['pickup_location'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $isImmediate = isset($_POST['is_immediate']) ? 1 : 0;

        // Validation
        if (empty($vehicleId) || empty($pickup) || empty($contact)) {
            $errors[] = "All fields marked with * are required.";
        }

        if (empty($errors)) {
            $result = bookEmergencyTow($userId, $vehicleId, $pickup, $contact, $description, $isImmediate);
            if ($result) {
                $success = true;
                header("Location: dashboard.php?tow_booking=success");
                exit;
            } else {
                $errors[] = "Failed to request tow. Please try again later.";
            }
        }
    }

    return ['errors' => $errors, 'success' => $success];
}

/**
 * Get data needed for the form
 */
function getTowFormData($userId)
{
    return getVehiclesByUserId($userId);
}
