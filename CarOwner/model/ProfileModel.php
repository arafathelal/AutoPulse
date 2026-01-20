<?php
require_once __DIR__ . '/../../CarOwner/config/db.php';

function getUserData($userId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id, name, email, phone, role, profile_picture FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUserData($userId, $name, $phone)
{
    global $conn;
    // Check if email/phone already exists for other users? 
    // For now, simple update based on requirements "don't do anything complex"

    $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
    return $stmt->execute([$name, $phone, $userId]);
}

function updateProfilePicture($userId, $filePath)
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
    return $stmt->execute([$filePath, $userId]);
}
