<?php
require_once(__DIR__ . '/../config.php');

class MessagerieController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getMessages($type, $recipient, $currentUserId) {
        if ($type === 'admin') {
            // Messages avec l'admin (id admin supposé = 1 ou autre)
            $stmt = $this->pdo->prepare("SELECT * FROM internal_messages WHERE (user_id = :user_id AND recipient_id = 1) OR (user_id = 1 AND recipient_id = :user_id) ORDER BY sent_at ASC");
            $stmt->execute([':user_id' => $currentUserId]);
        } elseif ($type === 'user' && $recipient) {
            // Messages entre $currentUserId et $recipient
            $stmt = $this->pdo->prepare("SELECT * FROM internal_messages WHERE (user_id = :user1 AND recipient_id = :user2) OR (user_id = :user2 AND recipient_id = :user1) ORDER BY sent_at ASC");
            $stmt->execute([':user1' => $currentUserId, ':user2' => $recipient]);
        } else {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

