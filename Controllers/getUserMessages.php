<?php
session_start();
require_once(__DIR__ . '/../config.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$userId = $_SESSION['user']['id'];
$adminId = 12;

try {
    $stmt = $pdo->prepare("
        SELECT m.id, m.message, m.sent_at,
               u.username AS sender,
               (m.user_id = :adminId) AS is_admin
        FROM internal_messages m
        JOIN users u ON m.user_id = u.id
        WHERE (m.user_id = :userId AND m.recipient_id = :adminId)
           OR (m.user_id = :adminId AND m.recipient_id = :userId)
        ORDER BY m.sent_at ASC
    ");
    $stmt->execute([
        'userId' => $userId,
        'adminId' => $adminId,
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    error_log("Erreur SQL: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur']);
}
