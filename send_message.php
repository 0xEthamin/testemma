<?php
require 'config.php';
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient = $_POST['recipient'];
    $message = htmlspecialchars($_POST['message']);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$recipient, $recipient]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("INSERT INTO internal_messages (user_id, message, sent_at) VALUES (?, ?, NOW())");
        $stmt->execute([$user['id'], $message]);
        header("Location: admin.php?message=success");
    } else {
        header("Location: admin.php?message=notfound");
    }
    exit;
}
?>
