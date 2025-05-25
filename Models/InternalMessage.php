<?php
class InternalMessage {
    public static function sendMessage($pdo, $userId, $message, $recipientId = 12, $isAdmin = false) {
        $stmt = $pdo->prepare("
            INSERT INTO internal_messages 
            (user_id, recipient_id, message, sent_at, is_admin, is_admin_message)
            VALUES (?, ?, ?, NOW(), ?, ?)
        ");
        return $stmt->execute([
            $userId,
            $recipientId,
            $message,
            $isAdmin ? 1 : 0,
            $isAdmin ? 1 : 0
        ]);
    }

    public static function sendMessage2($user_id = 12, $recipient_id, $message, $is_admin = false, $is_admin_message = false, $pdo) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO internal_messages 
                (user_id, recipient_id, message, sent_at, is_read, is_admin, is_admin_message)
                VALUES (?, ?, ?, NOW(), 0, ?, ?)
            ");
            $result = $stmt->execute([
                $user_id,
                $recipient_id,
                $message,
                $is_admin ? 1 : 0,
                $is_admin_message ? 1 : 0
            ]);
            
            return $result && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erreur dans sendMessage2: " . $e->getMessage());
            return false;
        }
    }


}