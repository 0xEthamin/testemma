<?php
session_start();
require_once(__DIR__ . '/../config.php'); 

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM logements WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: ../Views/admin.php");
exit;
