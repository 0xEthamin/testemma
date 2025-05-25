<?php


require_once __DIR__ . '/../Models/InternalMessage.php';
require_once __DIR__ . '/../config.php';  // <-- inclusion de la connexion PDO

session_start();


if (!isset($_SESSION['user']) || !isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != 1) {
    echo json_encode(['status' => 'error', 'error' => 'Accès non autorisé']);
    exit;
}

$user_id = $_POST['user_id'] ?? null;
$message = $_POST['message'] ?? null;

if (!$user_id || !$message) {
    echo json_encode(['status' => 'error', 'error' => 'Données manquantes']);
    exit;
}

$adminId = $_SESSION['user']['id'];

$success = InternalMessage::sendMessage2($adminId, $user_id, $message, true, true, $pdo);

if ($success) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => 'Échec de l\'insertion du message']);
}