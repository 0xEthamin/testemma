<?php
require_once '../config.php';
require_once '../controllers/AdminController.php';

session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    deleteUser($pdo, (int)$_GET['id']);
    header("Location: admin.php");
    exit;
} else {
    echo "Aucun utilisateur valide sélectionné.";
}
