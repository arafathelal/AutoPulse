<?php
require_once __DIR__ . '/../models/ServiceModel.php';
require_once __DIR__ . '/../models/PartsModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class ApiController
{
    private $serviceModel;
    private $partsModel;
    private $userModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
        $this->partsModel = new PartsModel();
        $this->userModel = new UserModel();
    }

    public function handleRequest()
    {
        header('Content-Type: application/json');

        // Simple router for API actions
        $action = $_GET['action'] ?? '';
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            switch ($action) {
                case 'get_all':
                    echo json_encode([
                        'services' => $this->serviceModel->getAll(),
                        'parts' => $this->partsModel->getAll(),
                        'users' => $this->userModel->getAll(),
                        'stats' => [
                            'services' => $this->serviceModel->count(),
                            'users' => $this->userModel->count(),
                            'low_stock' => $this->partsModel->countLowStock()
                        ]
                    ]);
                    break;

                case 'save_service':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? null;
                        $name = $_POST['name'] ?? '';
                        $price = $_POST['price'] ?? 0;

                        if ($id) {
                            $this->serviceModel->update($id, $name, $price);
                            echo json_encode(['success' => true, 'message' => 'Service updated']);
                        } else {
                            $this->serviceModel->create($name, $price);
                            echo json_encode(['success' => true, 'message' => 'Service created']);
                        }
                    }
                    break;

                case 'delete_service':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? 0;
                        $this->serviceModel->delete($id);
                        echo json_encode(['success' => true]);
                    }
                    break;

                case 'save_part':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? null;
                        $name = $_POST['name'] ?? '';
                        $price = $_POST['price'] ?? 0;
                        $stock = $_POST['stock'] ?? 0;
                        $img = $_POST['img'] ?? '';

                        if ($id) {
                            $this->partsModel->update($id, $name, $price, $stock, $img);
                            echo json_encode(['success' => true, 'message' => 'Part updated']);
                        } else {
                            $this->partsModel->create($name, $price, $stock, $img);
                            echo json_encode(['success' => true, 'message' => 'Part created']);
                        }
                    }
                    break;

                case 'delete_part':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? 0;
                        $this->partsModel->delete($id);
                        echo json_encode(['success' => true]);
                    }
                    break;

                case 'save_user':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? null;
                        $name = $_POST['name'] ?? '';
                        $email = $_POST['email'] ?? '';
                        $role = $_POST['role'] ?? 'Customer';

                        if ($id) {
                            $this->userModel->update($id, $name, $email, $role);
                            echo json_encode(['success' => true, 'message' => 'User updated']);
                        } else {
                            $this->userModel->create($name, $email, $role);
                            echo json_encode(['success' => true, 'message' => 'User created']);
                        }
                    }
                    break;

                case 'delete_user':
                    if ($method === 'POST') {
                        $id = $_POST['id'] ?? 0;
                        $this->userModel->delete($id);
                        echo json_encode(['success' => true]);
                    }
                    break;

                default:
                    echo json_encode(['error' => 'Invalid action']);
                    break;
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>