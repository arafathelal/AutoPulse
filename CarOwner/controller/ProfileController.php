<?php
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once __DIR__ . '/../model/ProfileModel.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'CarOwner') {
    header("Location: ../../Guest/public/index.php?page=login");
    exit();
}

$user_id = $_SESSION['user_id'];
$successMsg = "";
$errorMsg = "";

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Update Info
    if (isset($_POST['update_info'])) {
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);

        if (empty($name) || empty($phone)) {
            $errorMsg = "Name and Phone are required.";
        } else {
            if (updateUserData($user_id, $name, $phone)) {
                $_SESSION['user_name'] = $name; // Update session name
                $successMsg = "Profile updated successfully.";
            } else {
                $errorMsg = "Failed to update profile.";
            }
        }
    }

    // Update Profile Picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = $_FILES['profile_picture']['type'];
        $filesize = $_FILES['profile_picture']['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $errorMsg = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        } elseif ($filesize > 5 * 1024 * 1024) { // 5MB limit
            $errorMsg = "File size must be less than 5MB.";
        } else {
            // Upload directory
            $uploadDir = __DIR__ . '/../../assets/uploads/profile_pictures/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFilename = "user_" . $user_id . "_" . time() . "." . $ext;
            $uploadPath = $uploadDir . $newFilename;
            $dbPath = "assets/uploads/profile_pictures/" . $newFilename; // Path to store in DB (relative to base)

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadPath)) {
                if (updateProfilePicture($user_id, $dbPath)) {
                    $_SESSION['profile_picture'] = $dbPath; // Update session
                    $successMsg = "Profile picture updated successfully.";
                } else {
                    $errorMsg = "Failed to update database.";
                }
            } else {
                $errorMsg = "Failed to upload file.";
            }
        }
    }
}

// Fetch user data
$user = getUserData($user_id);
