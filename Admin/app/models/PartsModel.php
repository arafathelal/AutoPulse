<?php
require_once __DIR__ . '/../../config/database.php';

class PartsModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM parts ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $price, $stock, $image_url)
    {
        $stmt = $this->db->prepare("INSERT INTO parts (name, price, stock, image_url) VALUES (:name, :price, :stock, :img)");
        return $stmt->execute([
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'img' => $image_url
        ]);
    }

    public function update($id, $name, $price, $stock, $image_url)
    {
        $stmt = $this->db->prepare("UPDATE parts SET name = :name, price = :price, stock = :stock, image_url = :img WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'img' => $image_url
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM parts WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function countLowStock($threshold = 5)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM parts WHERE stock < :threshold");
        $stmt->execute(['threshold' => $threshold]);
        return $stmt->fetchColumn();
    }
}
?>