<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../mail.php';

class InscriptionController {
    private $pdo;
    private $userModel;
    private $mailService;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
        $this->mailService = new MailService();
    }

    public function inscrire() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $message = $this->processInscription();
            $this->afficherFormulaire($message);
        } else {
            $this->afficherFormulaire();
        }
    }

    private function processInscription(): string {
        $civility = $_POST['civility'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $firstname = trim($_POST['firstname'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $cgu_accepted = isset($_POST['cgu']);

        // Validation
        if (!$cgu_accepted) {
            return "Vous devez accepter les Conditions Générales d'Utilisation.";
        }

        if (empty($civility) || empty($name) || empty($firstname) || empty($username) || 
            empty($email) || empty($password) || empty($confirm_password) || empty($phone)) {
            return "Tous les champs sont requis.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Adresse email invalide.";
        }

        if (!preg_match('/^\+?[0-9\s\-]{7,15}$/', $phone)) {
            return "Numéro de téléphone invalide.";
        }

        if ($password !== $confirm_password) {
            return "Les mots de passe ne correspondent pas.";
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/', $password)) {
            return "Le mot de passe doit contenir au moins 8 caractères, une minuscule, une majuscule et un caractère spécial.";
        }

        if ($this->userModel->emailExists($email)) {
            return "Email déjà utilisé.";
        }

        if ($this->userModel->usernameExists($username)) {
            return "Nom d'utilisateur déjà utilisé.";
        }

        // Création de l'utilisateur
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(16));

        $success = $this->userModel->createUser($username, $firstname, $name, $email, $phone, $hashedPassword, $token);

        if ($success) {
            $this->sendVerificationEmail($email, $username, $token);
            return "Inscription réussie. Veuillez vérifier votre email pour activer votre compte.";
        }

        return "Une erreur s'est produite. Veuillez réessayer plus tard.";
    }

    private function sendVerificationEmail(string $email, string $username, string $token): void {
        $verificationLink = "http://localhost/Web-Mimba/verify.php?token=$token";
        $this->mailService->sendConfirmationEmail($email, $username, $verificationLink);
    }

    private function afficherFormulaire(string $message = ''): void {
        // Définir les variables nécessaires pour la vue
        $isSuccess = str_contains($message, 'Inscription réussie');
        
        // Chemin absolu vers la vue
        $viewPath = realpath(__DIR__ . '/../Views/creation_compte.php');
        
        if (!file_exists($viewPath)) {
            throw new RuntimeException("Vue introuvable: $viewPath");
        }

        // Inclure la vue
        include $viewPath;
    }
}