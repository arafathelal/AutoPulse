<?php
require_once __DIR__ . '/../../config/database.php';

class ServiceModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM service_catalog ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $price)
    {
        $stmt = $this->db->prepare("INSERT INTO service_catalog (name, price) VALUES (:name, :price)");
        return $stmt->execute(['name' => $name, 'price' => $price]);
    }

    public function update($id, $name, $price)
    {
        $stmt = $this->db->prepare("UPDATE service_catalog SET name = :name, price = :price WHERE id = :id");
        return $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM service_catalog WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM service_catalog")->fetchColumn();
    }
}
?>