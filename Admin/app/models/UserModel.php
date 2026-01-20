<?php
require_once __DIR__ . '/../../config/database.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $role)
    {
        // Password default or handle separately? Mock didn't have password.
        // We'll set a default placeholder password.
        $password = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, role, password) VALUES (:name, :email, :role, :pass)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'pass' => $password
        ]);
    }

    public function update($id, $name, $email, $role)
    {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'role' => $role
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function count()
    {
        return $this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}
?>