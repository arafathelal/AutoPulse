<?php
require_once __DIR__ . '/../config/db.php';

function registerUser($data)
{
    global $conn;

    // Hash password
    $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = 'CarOwner'; // Default role for public registration

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['fullName'],
        $data['email'],
        $data['phone'],
        $hashed_password,
        $role
    ]);
}

function loginUser($email, $password)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}

function getUserByEmail($email)
{
    global $conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}
