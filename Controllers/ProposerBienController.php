<?php

require_once(__DIR__ . '/../Models/Bien.php');
require_once(__DIR__ . '/../config.php');

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
        $zone = trim($_POST['zone'] ?? '');
        $region = trim($_POST['region'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            if (in_array($fileType, $allowedTypes)) {
                $uploadDir = __DIR__ . '/../uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid('img_') . '.' . $extension;
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/' . $fileName;
                } else {
                    $error = "Erreur lors de l'envoi de l'image.";
                }
            } else {
                $error = "Format d'image non supporté. Formats acceptés : JPEG, PNG, GIF, WEBP.";
            }
        }

        if (!$error && $titre && $description && $prix > 0 && $adresse) {
            try {
                $stmt = $this->pdo->query("SELECT MAX(id) AS max_id FROM logements");
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $id = ($result['max_id'] ?? 0) + 1;

                $sql = "INSERT INTO logements (id, Nom, Description, Adresse, photo, Zone, regions, mail, phone, est_valide)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    $id, $titre, $description, $adresse, $imagePath, $zone, $region, $email, $phone
                ]);

                $success = "Votre bien a été proposé avec succès. Il sera visible après validation.";
                $_POST = [];
            } catch (Exception $e) {
                $error = "Erreur lors de l'ajout du logement : " . $e->getMessage();
            }
        } elseif (!$error) {
            $error = "Veuillez remplir tous les champs correctement.";
        }

        include(__DIR__ . '/../Views/proposer_bien.php');
    }
}
