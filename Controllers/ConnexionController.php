<?php
class ConnexionController {
    private $pdo;
    

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function afficherFormulaire($error = '') {
        if (isset($_SESSION['user'])) {
            header('Location: /index.php?page=profil'); 
            exit;
        }

        include __DIR__ . '/../Views/connexion_form.php';
    }

    public function traiterConnexion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?page=connexion');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->afficherFormulaire("Veuillez remplir tous les champs.");
            return;
        }

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                if (isset($user['is_verified']) && !$user['is_verified']) {
                    $this->afficherFormulaire("Veuillez vérifier votre email avant de vous connecter.");
                    return;
                }

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'prenom' => $user['firstname'] ?? $user['prenom'] ?? '',
                    'name' => $user['name'] ?? $user['nom'] ?? '',
                    'phone' => $user['phone'] ?? $user['telephone'] ?? '',
                    'is_admin' => $user['is_admin'] ?? false
                ];

                if ($user['is_admin'] ?? false) {
                    $_SESSION['admin'] = true;
                }

                session_regenerate_id(true);

                $redirectPage = ($user['is_admin'] ?? false) ? 'admin' : 'profil';
                header("Location: /index.php?page=$redirectPage");
                exit;
            } else {
                $this->afficherFormulaire("Email ou mot de passe incorrect.");
            }
        } catch (PDOException $e) {
            error_log("Erreur de connexion: " . $e->getMessage());
            $this->afficherFormulaire("Une erreur est survenue. Veuillez réessayer.");
            }
    }

    public function connexion() {
        return $_SERVER['REQUEST_METHOD'] === 'POST' 
            ? $this->traiterConnexion() 
            : $this->afficherFormulaire();
    }
}