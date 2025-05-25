<?php
session_start();
require_once '../config.php'; // chemin vers ta connexion PDO

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE logements SET est_valide = 1 WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: ../Web-Mimba/Views/admin.php");
exit;
