<?php
require_once __DIR__ . '/config.php'; 
require_once __DIR__ . '/Controllers/FaqController.php';

$controller = new FaqController($pdo);
$controller->showFaq();
