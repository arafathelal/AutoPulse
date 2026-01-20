<?php
if (!isset($page_title))
    $page_title = "AutoPulse";
if (!isset($user_name))
    $user_name = "Guest";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= $page ?>.css">






</head>

<body>

    <nav class="navbar">
        <div class="brand">
            <a href="/WIP/AutoPulse/Guest/public/index.php" style="color: inherit; text-decoration: none;">
                <i class="fa-solid fa-car-side"></i> AutoPulse
            </a>
        </div>

        <div class="menu-toggle" id="mobile-menu"><i class="fa-solid fa-bars"></i></div>

        <ul class="nav-links" id="nav-list">
            <li><a href="dashboard.php" class="<?= $page == 'dashboard' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="vehicles.php" class="<?= $page == 'vehicles' ? 'active' : '' ?>">My Vehicles</a></li>
            <li><a href="booking_history.php" class="<?= $page == 'bookings' ? 'active' : '' ?>">Bookings</a></li>
            <li><a href="orders.php" class="<?= $page == 'orders' ? 'active' : '' ?>">Orders</a></li>
            <li><a href="buy_parts.php" class="<?= $page == 'parts' ? 'active' : '' ?>">Parts Store</a></li>
            <li><a href="profile.php" class="<?= $page == 'profile' ? 'active' : '' ?>">Profile</a></li>
            <li><a href="/WIP/AutoPulse/Guest/public/index.php?page=logout">Logout</a></li>
        </ul>

        <div class="user-section">
            <span
                style="color: white; font-weight: 600; font-size: 0.9rem;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?></span>
            <?php if (isset($_SESSION['profile_picture']) && !empty($_SESSION['profile_picture'])): ?>
                <img src="<?= BASE_URL ?>/../../<?= htmlspecialchars($_SESSION['profile_picture']) ?>" alt="Profile"
                    class="nav-profile-pic">
            <?php else: ?>
                <div class="profile-icon"><i class="fa-solid fa-user"></i></div>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container"><!-- PAGE CONTENT START -->