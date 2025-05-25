<?php
require_once __DIR__ . '/config.php'; // Assure-toi que ce fichier crée bien $pdo
require_once __DIR__ . '/Controllers/FaqController.php';

$controller = new FaqController($pdo);
$controller->showFaq();
