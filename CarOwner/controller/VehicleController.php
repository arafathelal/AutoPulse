<?php
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../model/VehicleModel.php";

/**
 * Handle the add vehicle form submission
 */
function handleAddVehicle()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'] ?? 1;
        $brand = trim($_POST['brand'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $year = intval($_POST['year'] ?? 0);
        $plateNumber = trim($_POST['plate_number'] ?? '');


        if (empty($brand) || empty($model) || empty($year) || empty($plateNumber)) {
            return "All fields are required.";
        }

        $success = addVehicle($userId, $brand, $model, $year, $plateNumber);

        if ($success) {
            header("Location: dashboard.php");
            exit;
        } else {
            return "Failed to add vehicle. Please try again.";
        }
    }
    return null;
}

/**
 * Get vehicles for the current user
 * 
 * @param int $userId
 * @return array
 */
function getUserVehicles($userId)
{
    return getVehiclesByUserId($userId);
}

/**
 * Handle vehicle deletion
 */
function handleDeleteVehicle()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        $userId = $_SESSION['user_id'] ?? 1;
        $vehicleId = intval($_POST['vehicle_id'] ?? 0);

        if ($vehicleId > 0) {
            deleteVehicle($vehicleId, $userId);
        }


        header("Location: vehicles.php");
        exit;
    }
}
