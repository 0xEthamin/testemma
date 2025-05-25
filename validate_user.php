<?php
require 'config.php';

// Vérification si l'utilisateur est administrateur
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Valider l'utilisateur
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Mise à jour du statut de vérification de l'utilisateur
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE id = ?");
    $stmt->execute([$user_id]);

    header("Location: admin.php"); // Rediriger vers le panneau d'administration
} else {
    echo "Aucun utilisateur trouvé.";
}
