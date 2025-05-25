<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../mail.php';

class PasswordController {
    private $pdo;
    private $mailService;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->mailService = new MailService();
    }

    // Affiche le formulaire "mot de passe oublié"
    public function forgotForm() {
        $success = $_SESSION['success'] ?? '';
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']);
        
        include __DIR__ . '/../Views/forgot_password.php';
    }

    // Traite la demande d'envoi de mail de réinitialisation
    public function sendResetLink() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $user = User::findByEmail($this->pdo, $email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', time() + 3600); // 1 heure de validité

                if (User::setResetToken($this->pdo, $user['id'], $token, $expires)) {
                    // Correction ici - Ajout du chemin /
                    $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/index.php?page=mot-de-passe&action=resetForm&token=$token";

                    try {
                        $this->mailService->sendResetEmail($email, $user['username'], $resetLink);
                        $_SESSION['success'] = "Un lien de réinitialisation a été envoyé à votre adresse email.";
                    } catch (Exception $e) {
                        $_SESSION['error'] = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
                    }
                } else {
                    $_SESSION['error'] = "Erreur lors de la génération du token.";
                }
            } else {
                $_SESSION['error'] = "Adresse email introuvable.";
            }
            header("Location: index.php?page=mot-de-passe&action=forgotForm");
            exit;
        }
    }

    // Affiche le formulaire de réinitialisation
    public function resetForm() {
        $token = $_GET['token'] ?? '';
        $user = User::findByResetToken($this->pdo, $token);

        if (!$user || strtotime($user['reset_expires']) < time()) {
            $_SESSION['error'] = "Le lien a expiré, veuillez réessayer.";
            header("Location: index.php?page=mot-de-passe&action=forgotForm");
            exit;
        }

        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        include __DIR__ . '/../Views/reset_password.php';
    }

    // Traite la réinitialisation du mot de passe
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = User::findByResetToken($this->pdo, $token);

            if (!$user || strtotime($user['reset_expires']) < time()) {
                $_SESSION['error'] = "Token invalide ou expiré.";
                header("Location: index.php?page=mot-de-passe&action=forgotForm");
                exit;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
                header("Location: index.php?page=mot-de-passe&action=resetForm&token=$token");
                exit;
            }

            if (User::updatePassword($this->pdo, $user['id'], password_hash($password, PASSWORD_DEFAULT))) {
                User::clearResetToken($this->pdo, $user['id']);
                $_SESSION['success'] = "Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.";
                header("Location: index.php?page=connexion");
                exit;
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de la réinitialisation du mot de passe.";
                header("Location: index.php?page=mot-de-passe&action=resetForm&token=$token");
                exit;
            }
        }
    }
}