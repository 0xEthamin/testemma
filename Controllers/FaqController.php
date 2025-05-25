<?php
require_once __DIR__ . '/../Models/FaqModel.php';

class FaqController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function showFaq() {
        $model = new FaqModel();
        $faqItems = $model->getFaqItems();
        require __DIR__ . '/../Views/faq.php';
    }
}
?>