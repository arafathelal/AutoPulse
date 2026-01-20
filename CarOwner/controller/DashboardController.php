<?php

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . '/../model/DashboardModel.php';

/**
 * Fetch all dashboard data and return it as an array
 */
function getDashboardData($userId)
{
    $stats = getDashboardStats($userId);
    $recent = getRecentActivities($userId);

    return [
        'stats' => $stats,
        'recent' => $recent
    ];
}
