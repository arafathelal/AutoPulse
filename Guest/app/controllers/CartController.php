<?php
require_once __DIR__ . '/../models/CartModel.php';

class CartController {
    public function add() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            $product_id = (int)($_POST['product_id'] ?? 0);
            if ($product_id > 0) {
                if (!isset($_SESSION['pending_cart'])) $_SESSION['pending_cart'] = [];
                $_SESSION['pending_cart'][] = $product_id;
                echo json_encode(['status' => 'redirect', 'message' => 'Please login to add to cart']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
            }
        } else {
            $product_id = (int)($_POST['product_id'] ?? 0);
            $quantity = (int)($_POST['quantity'] ?? 1);
            if ($product_id > 0 && $quantity > 0) {
                $model = new CartModel();
                if ($model->add($_SESSION['user_id'], $product_id, $quantity)) {
                    echo json_encode(['status' => 'success', 'message' => 'Added to cart']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
            }
        }
        exit;
    }

    public function update() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Login required']);
            exit;
        }
        $product_id = (int)($_POST['product_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        if ($product_id > 0 && $quantity >= 0) {
            $model = new CartModel();
            if ($model->updateQuantity($_SESSION['user_id'], $product_id, $quantity)) {
                echo json_encode(['status' => 'success', 'message' => 'Quantity updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
        exit;
    }

    public function remove() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Login required']);
            exit;
        }
        $product_id = (int)($_POST['product_id'] ?? 0);
        if ($product_id > 0) {
            $model = new CartModel();
            if ($model->remove($_SESSION['user_id'], $product_id)) {
                echo json_encode(['status' => 'success', 'message' => 'Removed from cart']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to remove']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product']);
        }
        exit;
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?page=login');
            exit;
        }
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM carts WHERE user_id = :uid');
        $stmt->execute(['uid' => $_SESSION['user_id']]);
        $_SESSION['checkout_success'] = 'Checkout completed successfully! Thank you for your purchase.';
        header('Location: ' . BASE_URL . 'index.php?page=cart');
        exit;
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'index.php?page=login');
            exit;
        }
        $model = new CartModel();
        $cartItems = $model->getByUser($_SESSION['user_id']);
        $total = $model->getTotal($_SESSION['user_id']);
        require_once __DIR__ . '/../views/cart.php';
    }
}