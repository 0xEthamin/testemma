<?php
class Message {
    public static function getAllMessages($pdo) {
        $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function getAllAdminMessages(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT m.*, u.username as sender
        FROM internal_messages m
        JOIN users u ON m.user_id = u.id
        WHERE m.is_admin_message = 1
        ORDER BY m.sent_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}