<?php
class ContactController {
    public function index() {
        require_once __DIR__ . '/../views/contact.php';
    }

    public function submit() {
        $errors = [];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name)) $errors[] = 'Name is required';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
        if (empty($message)) $errors[] = 'Message is required';

        if (empty($errors)) {
            // Simulate email send
            $_SESSION['contact_success'] = 'Message sent successfully!';
        } else {
            $_SESSION['contact_errors'] = $errors;
        }
        header('Location: ' . BASE_URL . 'index.php?page=contact');
        exit;
    }
}