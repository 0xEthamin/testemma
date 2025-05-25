<?php
require_once(__DIR__ . '/Controllers/ConnexionController.php');

$controller = new ConnexionController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->traiterConnexion();
} else {
    $controller->afficherFormulaire();
}
