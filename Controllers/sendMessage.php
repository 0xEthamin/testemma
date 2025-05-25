<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/InternalMessage.php';

header('Content-Type: application/json');

if (!isset($_POST['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userId = $_POST['user_id'];
$message = trim($_POST['message'] ?? '');

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Empty message']);
    exit;
}

try {
    $success = InternalMessage::sendMessage($pdo, $userId, $message);
    echo json_encode(['status' => $success ? 'success' : 'error']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>