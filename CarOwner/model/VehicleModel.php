<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Add a new vehicle for a user
 * 
 * @param int $userId
 * @param string $brand
 * @param string $model
 * @param int $year
 * @param string $plateNumber
 * @return bool
 */
function addVehicle($userId, $brand, $model, $year, $plateNumber)
{
    global $conn;

    try {
        $stmt = $conn->prepare("INSERT INTO vehicles (user_id, brand, model, year, plate_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $brand, $model, $year, $plateNumber]);
        return true;
    } catch (PDOException $e) {
        // Log error silently usually, or throw
        die("Error adding vehicle: " . $e->getMessage());
    }
}

/**
 * Get all vehicles for a specific user
 * 
 * @param int $userId
 * @return array
 */
function getVehiclesByUserId($userId)
{
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT * FROM vehicles WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching vehicles: " . $e->getMessage());
    }
}

/**
 * Delete a vehicle
 * 
 * @param int $vehicleId
 * @param int $userId
 * @return bool
 */
function deleteVehicle($vehicleId, $userId)
{
    global $conn;

    try {
        $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ? AND user_id = ?");
        $stmt->execute([$vehicleId, $userId]);
        return $stmt->rowCount() > 0;
    } catch (PDOException $e) {
        die("Error deleting vehicle: " . $e->getMessage());
    }
}
