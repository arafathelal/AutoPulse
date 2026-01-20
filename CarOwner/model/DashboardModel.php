<?php
require_once __DIR__ . "/../config/db.php";

/**
 * Return summary counts for dashboard
 */

function getDashboardStats($userId)
{
    global $conn;


    $stmt = $conn->prepare("SELECT COUNT(*) FROM vehicles WHERE user_id = ?");
    $stmt->execute([$userId]);
    $total_vehicles = $stmt->fetchColumn();


    $stmt = $conn->prepare("SELECT COUNT(*) FROM services WHERE user_id = ? AND status != 'Completed'");
    $stmt->execute([$userId]);
    $active_services = $stmt->fetchColumn();


    $stmt = $conn->prepare("SELECT COUNT(*) FROM part_orders WHERE user_id = ?");
    $stmt->execute([$userId]);
    $parts_orders = $stmt->fetchColumn();


    $stmt = $conn->prepare("SELECT COALESCE(SUM(total_price),0) FROM part_orders WHERE user_id = ?");
    $stmt->execute([$userId]);
    $spent = $stmt->fetchColumn();
    $total_spent = "৳" . number_format($spent, 2);

    return [
        'total_vehicles' => $total_vehicles,
        'active_services' => $active_services,
        'parts_orders' => $parts_orders,
        'total_spent' => $total_spent
    ];
}

/**
 * Return recent activity (services + part orders)
 */
function getRecentActivities($userId)
{
    global $conn;


    $sql = "
        (SELECT 
            'Service' AS type,
            service_type AS description,
            created_at AS date,
            status
        FROM services
        WHERE user_id = ?)
        UNION ALL
        (SELECT 
            'Part Order' AS type,
            p.name AS description,
            po.created_at AS date,
            po.status
        FROM part_orders po
        JOIN parts p ON po.part_id = p.id
        WHERE po.user_id = ?)
        ORDER BY date DESC
        LIMIT 5
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId, $userId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}
