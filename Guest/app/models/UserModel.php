<?php
require_once __DIR__ . '/../../database.php';

class UserModel {
    public function findByEmail($email) {
        $db = getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new user.
     * Returns true on success, false on failure (including duplicate email).
     *
     * Note: role parameter is ignored/enforced to 'user' to prevent
     * privilege escalation via registration form.
     */
    public function create($name, $email, $password, $phone, $role = 'CarOwner') {
        $db = getDB();

        // Enforce role to 'CarOwner' regardless of input for now, or respect the default
        // The previous code forced 'user', but 'CarOwner' is the valid enum.
        $role = 'CarOwner';

        // Check if email already exists
        $stmt = $db->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        if ($stmt->fetchColumn()) {
            return false; // caller will treat this as "email already exists"
        }

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $stmt = $db->prepare('INSERT INTO users (name, email, password, phone, role) VALUES (:name, :email, :password, :phone, :role)');
        try {
            return (bool)$stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'phone' => $phone,
                'role' => $role
            ]);
        } catch (PDOException $e) {
            // swallow and return false for controller to handle (log in production)
            return false;
        }
    }
}