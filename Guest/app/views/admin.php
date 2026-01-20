<?php require_once 'header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>CSS/admin.css">

<h2>Admin Panel - Manage Products</h2>
<?php if (isset($_SESSION['admin_success'])): ?>
    <p><?php echo htmlspecialchars($_SESSION['admin_success']); ?></p>
    <?php unset($_SESSION['admin_success']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['admin_errors'])): ?>
    <ul>
        <?php foreach ($_SESSION['admin_errors'] as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
    </ul>
    <?php unset($_SESSION['admin_errors']); ?>
<?php endif; ?>
<?php foreach ($products as $product): ?>
    <div class="product">
        <h3>Edit <?php echo htmlspecialchars($product['name']); ?></h3>
        <form method="POST" action="index.php?page=admin">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <label>Name:</label><input type="text" name="name"
                value="<?php echo htmlspecialchars($product['name']); ?>"><br>
            <label>Description:</label><textarea
                name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
            <label>Price:</label><input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>"><br>
            <label>Image URL:</label><input type="url" name="image_url"
                value="<?php echo htmlspecialchars($product['image_url']); ?>"><br>
            <button type="submit">Update</button>
        </form>
    </div>
<?php endforeach; ?>
<?php require_once 'footer.php'; ?>