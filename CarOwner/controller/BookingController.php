<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../model/HomeServiceModel.php";
require_once __DIR__ . "/../model/EmergencyModel.php";
require_once __DIR__ . "/../model/BookingModel.php";

/**
 * Get aggregated booking history
 */
function getFullBookingHistory($userId)
{
    $homeServices = getHomeServiceHistory($userId);
    $emergencyTows = getEmergencyTowHistory($userId);

    // Merge and sort by date desc
    $allBookings = array_merge($homeServices, $emergencyTows);

    usort($allBookings, function ($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });

    return $allBookings;
}

/**
 * Handle cancellation request
 */
function handleCancelBooking()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
        $userId = $_SESSION['user_id'] ?? 1; // Fallback
        $serviceId = intval($_POST['cancel_booking_id']);

        if (cancelService($serviceId, $userId)) {
            header("Location: booking_history.php?msg=cancelled");
            exit;
        } else {
            header("Location: booking_history.php?error=failed_cancel");
            exit;
        }
    }
}
