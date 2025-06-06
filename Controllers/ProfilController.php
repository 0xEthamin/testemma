<?php
require_once(__DIR__ . '/../Models/User.php');

class ProfilController { 
    private $pdo;
    
    public function __construct($pdo) {
        if (!isset($_SESSION['user'])) {
            header("Location: /index.php?page=connexion");
            exit;
        }
        $this->pdo = $pdo;
    }

    public function showProfile() {
        $user = $_SESSION['user'];

        if (!$user) {
            $_SESSION['error'] = "Utilisateur introuvable.";
            header("Location: index.php?page=home");
            exit;
        }
        $pdo = $this->pdo;
        include(__DIR__ . '/../Views/profil.php');
    }

    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=profil");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $newName = trim($_POST['name'] ?? '');
        $newEmail = trim($_POST['email'] ?? '');
        $newPhone = trim($_POST['phone'] ?? '');

        if (empty($newName) || empty($newEmail) || empty($newPhone)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
            header("Location: index.php?page=profil");
            exit;
        }

        try {
            User::updateProfile($userId, $newName, $newEmail, $newPhone);
            $_SESSION['success'] = "Profil mis à jour avec succès!";
            
            $_SESSION['user_name'] = $newName;
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de la mise à jour: " . $e->getMessage();
        }

        header("Location: index.php?page=profil");
        exit;
    }
}