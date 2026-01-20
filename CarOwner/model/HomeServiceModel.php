<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Handle Home Service Bookings
 */
function bookHomeService($userId, $vehicleId, $serviceType, $date, $time, $address, $contact, $notes)
{
    global $conn;

    try {
        $conn->beginTransaction();

        // 1. Insert into main services table
        $stmt = $conn->prepare("INSERT INTO services (user_id, service_type, status, created_at) VALUES (?, ?, 'Pending', NOW())");
        $stmt->execute([$userId, $serviceType]);
        $serviceId = $conn->lastInsertId();

        // 2. Insert into home_services table
        $stmt = $conn->prepare("INSERT INTO home_services (service_id, vehicle_id, scheduled_date, scheduled_time, address, contact_number, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$serviceId, $vehicleId, $date, $time, $address, $contact, $notes]);

        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Error booking home service: " . $e->getMessage());
        return false;
    }
}

/**
 * Get Service History
 * Joins services and home_services
 */
function getHomeServiceHistory($userId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT s.id, s.service_type, s.status, s.created_at, 
                   hs.scheduled_date, hs.scheduled_time, hs.address, v.brand, v.model, v.plate_number
            FROM services s
            JOIN home_services hs ON s.id = hs.service_id
            JOIN vehicles v ON hs.vehicle_id = v.id
            WHERE s.user_id = ?
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching service history: " . $e->getMessage());
        return [];
    }
}
