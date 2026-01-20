<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Get all available parts
 */
function getAllParts()
{
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM parts WHERE stock > 0 ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching parts: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single part by ID
 */
function getPartById($id)
{
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM parts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching part: " . $e->getMessage());
        return null;
    }
}

/**
 * Create a new part order
 */
function createPartOrder($userId, $partId, $quantity, $totalPrice, $address, $contact)
{
    global $conn;
    try {
        $conn->beginTransaction();


        $stmt = $conn->prepare("SELECT stock FROM parts WHERE id = ? FOR UPDATE");
        $stmt->execute([$partId]);
        $part = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$part || $part['stock'] < $quantity) {
            $conn->rollBack();
            return "Insufficient stock.";
        }


        $stmt = $conn->prepare("UPDATE parts SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $partId]);


        $stmt = $conn->prepare("INSERT INTO part_orders (user_id, part_id, quantity, total_price, delivery_address, contact_number, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())");
        $stmt->execute([$userId, $partId, $quantity, $totalPrice, $address, $contact]);

        $conn->commit();
        return true;

    } catch (PDOException $e) {
        $conn->rollBack();
        error_log("Error creating part order: " . $e->getMessage());
        return "Database error occurred.";
    }
}

/**
 * Get all orders for a specific user
 */
function getOrdersByUserId($userId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT po.*, p.name as part_name, p.image_url 
            FROM part_orders po 
            JOIN parts p ON po.part_id = p.id 
            WHERE po.user_id = ? 
            ORDER BY po.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching user orders: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single order by ID
 */
function getOrderById($orderId)
{
    global $conn;
    try {
        $stmt = $conn->prepare("
            SELECT po.*, p.name as part_name, p.image_url, p.description, p.price as unit_price 
            FROM part_orders po 
            JOIN parts p ON po.part_id = p.id 
            WHERE po.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching order details: " . $e->getMessage());
        return null;
    }
}
