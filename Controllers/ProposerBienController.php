<?php

require_once __DIR__ . '/../Models/Bien.php';

class ProposerBienController {
    private $pdo;
    private $bienModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->bienModel = new Bien($pdo);
    }

    public function showForm() {
        $success = '';
        $error = '';
        include(__DIR__ . '/../Views/proposer_bien.php');
    }

    public function handleForm() {
        $success = '';
        $error = '';

        $titre = trim($_POST['titre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $prix = floatval($_POST['prix'] ?? 0);
        $adresse = trim($_POST['adresse'] ?? '');

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            if (in_array($fileType, $allowedTypes)) {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $fileName = uniqid('img_') . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/' . $fileName;
                } else {
                    $error = "Erreur lors de l'envoi de l'image.";
                }
            } else {
                $error = "Format d'image non supporté.";
            }
        }

        if (!$error && $titre && $description && $prix > 0 && $adresse) {
            if ($this->bienModel->ajouterBien($titre, $description, $prix, $adresse, $imagePath)) {
                $success = "Votre bien a été proposé avec succès !";
                // Réinitialiser $_POST
                $_POST = [];
            } else {
                $error = "Erreur lors de l'ajout du bien.";
            }
        } elseif (!$error) {
            $error = "Veuillez remplir tous les champs correctement.";
        }

        include 'views/proposer_bien.php';
    }
}