<?php
// Load database bootstrap relative to this file's directory
$dbPath = __DIR__ . '/../../database.php';
$realDbPath = realpath($dbPath);

if (!$realDbPath || !is_readable($realDbPath)) {
    // Helpful debugging message — change to exception or error log in production
    die("Required file not found or unreadable: $dbPath (resolved: " . var_export($realDbPath, true) . ")");
}

require_once $realDbPath;

class PartsModel
{
    // Used to be getAllDiscounted, now just gets recent parts
    public function getAllDiscounted()
    {
        $db = getDB();
        // 'parts' table has no discount column, changing to select all or some featured parts
        $stmt = $db->query('SELECT * FROM parts ORDER BY created_at DESC LIMIT 6');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $db = getDB();
        $stmt = $db->query('SELECT * FROM parts');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM parts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $description, $price, $image_url)
    {
        $db = getDB();
        // Removed discount column update as it does not exist in 'parts' table
        $stmt = $db->prepare('UPDATE parts SET name = :name, description = :description, price = :price, image_url = :image_url WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image_url' => $image_url
        ]);
    }
}
