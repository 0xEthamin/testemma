<?php
class ConnexionController {
    private $pdo;
    

    public function __construct($pdo) {
        // La session est déjà démarrée dans index.php, pas besoin de la redémarrer
        $this->pdo = $pdo;
    }

    public function afficherFormulaire($error = '') {
        if (isset($_SESSION['user'])) {
            header('Location: /Web-Mimba/index.php?page=profil'); // Chemin absolu
            exit;
        }

        include __DIR__ . '/../Views/connexion_form.php';
    }

    public function traiterConnexion() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Web-Mimba/index.php?page=connexion');
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

                // Stockez TOUTES les données nécessaires pour le profil
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'prenom' => $user['firstname'] ?? $user['prenom'] ?? '',
                    'name' => $user['name'] ?? $user['nom'] ?? '',
                    'phone' => $user['phone'] ?? $user['telephone'] ?? '',
                    'is_admin' => $user['is_admin'] ?? false
                ];

                // Définir également la variable de session admin si l'utilisateur est admin
                if ($user['is_admin'] ?? false) {
                    $_SESSION['admin'] = true;
                }

                session_regenerate_id(true);

                // Redirection avec chemin absolu
                $redirectPage = ($user['is_admin'] ?? false) ? 'admin' : 'profil';
                header("Location: /Web-Mimba/index.php?page=$redirectPage");
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
        // Méthode plus concise
        return $_SERVER['REQUEST_METHOD'] === 'POST' 
            ? $this->traiterConnexion() 
            : $this->afficherFormulaire();
    }
}