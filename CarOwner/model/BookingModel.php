<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Cancel a service booking
 */
function cancelService($serviceId, $userId)
{
    global $conn;
    try {
        // Only allow cancelling if it belongs to the user and is 'Pending'
        $stmt = $conn->prepare("UPDATE services SET status = 'Cancelled' WHERE id = ? AND user_id = ? AND status = 'Pending'");
        $stmt->execute([$serviceId, $userId]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        error_log("Error cancelling service: " . $e->getMessage());
        return false;
    }
}
