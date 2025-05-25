<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

// Vérification stricte de l'accès
if (empty($_SESSION['admin'])) {
    http_response_code(403);
    exit(json_encode(['error' => 'Accès non autorisé']));
}

$adminId = 12; // ✅ ID fixe de l'admin
$userId = filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT);

if ($userId === false || $userId === null) {
    http_response_code(400);
    exit(json_encode(['error' => 'ID utilisateur invalide']));
}

try {
    $query = "SELECT m.id, m.message, m.sent_at, 
                     u.username as sender, 
                     (m.user_id = :adminId) as is_admin
              FROM internal_messages m
              JOIN users u ON m.user_id = u.id
              WHERE (m.user_id = :userId AND m.recipient_id = :adminId)
                 OR (m.user_id = :adminId AND m.recipient_id = :userId)
              ORDER BY m.sent_at ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'adminId' => $adminId,
        'userId' => $userId,
    ]);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    exit;

} catch (PDOException $e) {
    error_log("PDO Error: " . $e->getMessage());
    http_response_code(500);
    exit(json_encode(['error' => 'Erreur serveur']));
}
