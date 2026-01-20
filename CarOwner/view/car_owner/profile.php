<?php
$page = 'profile';
$page_title = 'My Profile';
require_once __DIR__ . '/../../controller/ProfileController.php';
require_once __DIR__ . '/../layout/header.php';
?>

<div class="profile-container">
    <div class="profile-header">
        <h2>My Profile</h2>
    </div>

    <?php if ($successMsg): ?>
        <div class="alert success">
            <?= htmlspecialchars($successMsg) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert error">
            <?= htmlspecialchars($errorMsg) ?>
        </div>
    <?php endif; ?>

    <div class="profile-content">
        <div class="profile-card">
            <div class="profile-image-section">
                <?php
                $profilePic = !empty($user['profile_picture']) ? "../../../" . $user['profile_picture'] : "../../../assets/images/default-user.png";
                // Note: Path adjustment might be needed depending on where images are stored relative to this file.
                // $user['profile_picture'] stores "assets/uploads/..."
                // We are in view/car_owner/profile.php. 
                // Assets are in root/assets
                // So ../../../assets is correct if root is 3 levels up from view/car_owner
                // Wait. view/car_owner/profile.php -> view/car_owner -> view -> CarOwner -> Root? No.
                // e:\Web Project Demo\AutoPulseDev\CarOwner\view\car_owner\profile.php
                // e:\Web Project Demo\AutoPulseDev\assets
                // So: ../../../assets is correct.
                // However, let's double check if default-user.png exists or use a placeholder.
                // I'll stick to a generic font-awesome icon if image is missing/broken, or just the image tag.
                ?>
                <img src="<?= htmlspecialchars($profilePic) ?>" alt="Profile Picture" class="profile-pic"
                    onerror="this.src='https://via.placeholder.com/150'">

                <form action="" method="POST" enctype="multipart/form-data" class="upload-form">
                    <label for="profile_picture" class="btn-upload">Change Photo</label>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                        onchange="this.form.submit()" hidden>
                </form>
            </div>

            <div class="profile-details-section">
                <form action="" method="POST" class="profile-form">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>"
                            readonly class="readonly-input">
                        <small>Email cannot be changed.</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                            required>
                    </div>

                    <!-- Role (Read Only) -->
                    <div class="form-group">
                        <label>Account Type</label>
                        <input type="text" value="<?= htmlspecialchars($user['role']) ?>" readonly
                            class="readonly-input">
                    </div>

                    <div class="form-actions">
                        <button type="submit" name="update_info" class="btn-save">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Internal CSS for User Profile */
    .profile-container {
        max-width: 800px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        padding: 2rem;
    }

    .profile-header h2 {
        color: #333;
        margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .alert {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .alert.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .profile-content {
        display: flex;
        flex-direction: row;
        gap: 30px;
    }

    .profile-image-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 1px solid #f0f0f0;
        padding-right: 20px;
    }

    .profile-pic {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #007bff;
        margin-bottom: 15px;
    }

    .btn-upload {
        display: inline-block;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background 0.3s;
    }

    .btn-upload:hover {
        background-color: #0056b3;
    }

    .profile-details-section {
        flex: 2;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }

    .readonly-input {
        background-color: #e9ecef;
        cursor: not-allowed;
    }

    .btn-save {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn-save:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        .profile-content {
            flex-direction: column;
        }

        .profile-image-section {
            border-right: none;
            border-bottom: 1px solid #f0f0f0;
            padding-right: 0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
    }
</style>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>