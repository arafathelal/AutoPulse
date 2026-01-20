<?php
// Require database.php relative to this file
$dbPath = __DIR__ . '/../../database.php';
$realDbPath = realpath($dbPath);
if (!$realDbPath || !is_readable($realDbPath)) {
    die("Required file not found or unreadable: $dbPath (resolved: " . var_export($realDbPath, true) . ")");
}
require_once $realDbPath;

class CartModel
{
    public function add($user_id, $product_id, $quantity = 1)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT quantity FROM carts WHERE user_id = :uid AND product_id = :pid');
        $stmt->execute(['uid' => $user_id, 'pid' => $product_id]);
        $existing = $stmt->fetchColumn();
        if ($existing) {
            $stmt = $db->prepare('UPDATE carts SET quantity = quantity + :qty WHERE user_id = :uid AND product_id = :pid');
        } else {
            $stmt = $db->prepare('INSERT INTO carts (user_id, product_id, quantity) VALUES (:uid, :pid, :qty)');
        }
        return $stmt->execute(['uid' => $user_id, 'pid' => $product_id, 'qty' => $quantity]);
    }

    public function updateQuantity($user_id, $product_id, $quantity)
    {
        $db = getDB();
        if ($quantity < 1) {
            return $this->remove($user_id, $product_id);
        }
        $stmt = $db->prepare('UPDATE carts SET quantity = :qty WHERE user_id = :uid AND product_id = :pid');
        return $stmt->execute(['qty' => $quantity, 'uid' => $user_id, 'pid' => $product_id]);
    }

    public function remove($user_id, $product_id)
    {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM carts WHERE user_id = :uid AND product_id = :pid');
        return $stmt->execute(['uid' => $user_id, 'pid' => $product_id]);
    }

    public function getByUser($user_id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT c.*, p.name, p.price, p.image_url FROM carts c JOIN parts p ON c.product_id = p.id WHERE c.user_id = :uid');
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotal($user_id)
    {
        $db = getDB();
        $stmt = $db->prepare('SELECT SUM(p.price * c.quantity) AS total FROM carts c JOIN parts p ON c.product_id = p.id WHERE c.user_id = :uid');
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchColumn() ?: 0.00;
    }
}