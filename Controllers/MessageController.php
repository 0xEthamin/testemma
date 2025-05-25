<?php
class MessageController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function showMessagingInterface() {
        $type = $_GET['type'] ?? '';
        $recipient = $_GET['recipient'] ?? '';
        
        if ($type === 'admin') {
            $this->showAdminConversation();
        } elseif ($type === 'user') {
            if (empty($recipient)) {
                $this->showUserSearch();
            } else {
                $this->showUserConversation($recipient);
            }
        } else {
            include __DIR__ . '/../Views/choix_messagerie.php';
        }
    }

    private function showAdminConversation() {
        // Charger les messages avec l'admin
        $userId = $_SESSION['user']['id'];
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username as sender 
            FROM internal_messages m
            JOIN users u ON m.user_id = u.id
            WHERE (m.user_id = ? AND m.is_admin_message = 1)
               OR (u.admin = 1 AND m.recipient_id = ?)
            ORDER BY m.sent_at ASC
        ");
        $stmt->execute([$userId, $userId]);
        $messages = $stmt->fetchAll();

        include __DIR__ . '/../Views/messagerie.php';
    }

    private function showUserSearch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchTerm = trim($_POST['search']);
            $stmt = $this->pdo->prepare("
                SELECT id, username, email 
                FROM users 
                WHERE (username LIKE ? OR email LIKE ?) 
                AND id != ?
                AND verified = 1
            ");
            $stmt->execute(["%$searchTerm%", "%$searchTerm%", $_SESSION['user']['id']]);
            $searchResults = $stmt->fetchAll();
        }

        include __DIR__ . '/../Views/recherche_utilisateur.php';
    }

    private function showUserConversation($recipient) {
        // Vérifier que le destinataire existe
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
        $stmt->execute([$recipient, $recipient, $_SESSION['user']['id']]);
        $recipientUser = $stmt->fetch();

        if (!$recipientUser) {
            $_SESSION['error_message'] = "Utilisateur non trouvé";
            header("Location: index.php?page=messagerie&type=user");
            exit;
        }

        // Charger les messages avec cet utilisateur
        $stmt = $this->pdo->prepare("
            SELECT m.*, u.username as sender 
            FROM internal_messages m
            JOIN users u ON m.user_id = u.id
            WHERE (m.user_id = ? AND m.recipient_id = ?)
               OR (m.user_id = ? AND m.recipient_id = ?)
            ORDER BY m.sent_at ASC
        ");
        $stmt->execute([
            $_SESSION['user']['id'], 
            $recipientUser['id'],
            $recipientUser['id'],
            $_SESSION['user']['id']
        ]);
        $messages = $stmt->fetchAll();

        include __DIR__ . '/../Views/messagerie.php';
    }

    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("HTTP/1.1 405 Method Not Allowed");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $message = trim($_POST['message'] ?? '');
        $recipient = trim($_POST['recipient'] ?? '');
        $isAdminMessage = isset($_POST['is_admin']) && $_POST['is_admin'] === '1';

        if (empty($message)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['status' => 'error', 'message' => 'Message vide']);
            exit;
        }

        try {
            if ($isAdminMessage) {
                // Message à l'admin
                $stmt = $this->pdo->prepare("
                    INSERT INTO internal_messages 
                    (user_id, message, sent_at, is_admin_message) 
                    VALUES (?, ?, NOW(), 1)
                ");
                $stmt->execute([$userId, $message]);
            } elseif (!empty($recipient)) {
                // Message à un autre utilisateur
                $stmt = $this->pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                $stmt->execute([$recipient, $recipient, $userId]);
                $recipientUser = $stmt->fetch();

                if (!$recipientUser) {
                    header("HTTP/1.1 404 Not Found");
                    echo json_encode(['status' => 'error', 'message' => 'Destinataire non trouvé']);
                    exit;
                }

                $stmt = $this->pdo->prepare("
                    INSERT INTO internal_messages 
                    (user_id, recipient_id, message, sent_at) 
                    VALUES (?, ?, ?, NOW())
                ");
                $stmt->execute([$userId, $recipientUser['id'], $message]);
            } else {
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['status' => 'error', 'message' => 'Destinataire manquant']);
                exit;
            }

            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            error_log("Erreur d'envoi de message: " . $e->getMessage());
            header("HTTP/1.1 500 Internal Server Error");
            echo json_encode(['status' => 'error', 'message' => 'Erreur serveur']);
        }
    }
}