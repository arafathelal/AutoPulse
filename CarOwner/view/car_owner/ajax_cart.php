<?php
session_start();
require_once __DIR__ . "/../../controller/PartsController.php";

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $partId = intval($_POST['part_id'] ?? 0);
    $qty = intval($_POST['quantity'] ?? 1);

    $result = addToCart($partId, $qty);
    echo json_encode($result);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid action']);
