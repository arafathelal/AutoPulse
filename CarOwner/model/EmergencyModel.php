<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Handle Emergency Tow Requests
 */
function bookEmergencyTow($userId, $vehicleId, $pickup, $contact, $description, $isImmediate)
{
    global $conn;

    try {
        $conn->beginTransaction();

        // 1. Insert into main services table
        $stmt = $conn->prepare("INSERT INTO services (user_id, service_type, status, created_at) VALUES (?, 'Emergency Tow', 'Pending', NOW())");
        $stmt->execute([$userId]);
        $serviceId = $conn->lastInsertId();

        // 2. Insert into emergency_tows table
        $stmt = $conn->prepare("INSERT INTO emergency_tows (service_id, vehicle_id, pickup_location, contact_number, description, is_immediate) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$serviceId, $vehicleId, $pickup, $contact, $description, $isImmediate]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Error booking emergency tow: " . $e->getMessage());
        return false;
    }
}

/**
 * Get Emergency Tow History
 * Joins services and emergency_tows
 */
function getEmergencyTowHistory($userId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT s.id, s.service_type, s.status, s.created_at, 
                   et.pickup_location, et.contact_number, et.is_immediate, v.brand, v.model, v.plate_number
            FROM services s
            JOIN emergency_tows et ON s.id = et.service_id
            JOIN vehicles v ON et.vehicle_id = v.id
            WHERE s.user_id = ?
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching emergency tow history: " . $e->getMessage());
        return [];
    }
}
