<?php
require_once __DIR__ . "/../model/HomeServiceModel.php";
require_once __DIR__ . "/../model/VehicleModel.php";

/**
 * Handle the Book Service form submission
 */
function handleBookService()
{
    global $conn;
    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'] ?? 1;

        $vehicleId = intval($_POST['vehicle_id'] ?? 0);
        $serviceType = trim($_POST['service_type'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $time = trim($_POST['time'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $contact = trim($_POST['contact'] ?? '');
        $notes = trim($_POST['notes'] ?? '');


        if (empty($vehicleId) || empty($serviceType) || empty($date) || empty($time) || empty($address) || empty($contact)) {
            $errors[] = "All fields marked with * are required.";
        }


        if (!empty($date) && strtotime($date) < strtotime(date('Y-m-d'))) {
            $errors[] = "Service date cannot be in the past.";
        }

        if (empty($errors)) {
            $result = bookHomeService($userId, $vehicleId, $serviceType, $date, $time, $address, $contact, $notes);
            if ($result) {
                $success = true;

                header("Location: dashboard.php?booking=success");
                exit;
            } else {
                $errors[] = "Failed to book service. Please try again later.";
            }
        }
    }

    return ['errors' => $errors, 'success' => $success];
}

/**
 * Get data needed for the booking form
 */
function getBookingFormData($userId)
{
    $vehicles = getVehiclesByUserId($userId);

    require_once __DIR__ . '/../../Guest/config.php';
    require_once __DIR__ . '/../../Admin/app/models/ServiceModel.php';

    $serviceModel = new ServiceModel();
    $services = $serviceModel->getAll();

    return ['vehicles' => $vehicles, 'services' => $services];
}
