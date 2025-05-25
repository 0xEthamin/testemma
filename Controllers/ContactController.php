<?php
class ContactController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function afficherFormulaire() {
        $success = false;
        $error = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->traiterFormulaire();
            $success = $result['success'];
            $error = !$success;
        }
        
        include __DIR__ . '/../Views/contact_form.php';
    }

    private function traiterFormulaire() {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        if (empty($name) || empty($email) || empty($message)) {
            return ['success' => false];
        }

        // Enregistrement en base de données
        try {
            $stmt = $this->pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $message]);
            return ['success' => true];
        } catch (Exception $e) {
            error_log("Erreur lors de l'enregistrement du message: " . $e->getMessage());
            return ['success' => false];
        }
    }
}