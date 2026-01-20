<?php
// Main Router for Admin Module

// Basic AutoLoader or include
require_once __DIR__ . '/../app/controllers/DashboardController.php';
require_once __DIR__ . '/../app/controllers/ApiController.php';

$request = $_GET['request'] ?? 'dashboard';

// Route
switch ($request) {
    case 'api':
        $controller = new ApiController();
        $controller->handleRequest();
        break;

    case 'dashboard':
    default:
        $controller = new DashboardController();
        $controller->index();
        break;
}
?>