<?php
require_once __DIR__ . '/../../database.php';

class ServiceModel
{
    protected $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        // Alias price as base_price to keep controller logic compatible
        $stmt = $this->db->query('SELECT id, name, price as base_price FROM service_catalog ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT id, name, price as base_price FROM service_catalog WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}