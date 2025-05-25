<?php

session_start();
require_once(__DIR__ . '/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);



// ROUTEUR PRINCIPAL
$action = $_GET['action'] ?? 'home';
$page = $_GET['page'] ?? 'home';

try {
    switch ($page) {
        case 'home':
            require_once __DIR__ . '/Controllers/LogementController.php';
            $controller = new LogementController($pdo);
            $controller->accueil();
            break;
        case 'recherche':
        case 'logement-liste':
        case 'logement':
            require_once __DIR__ . '/Controllers/LogementController.php';
            $controller = new LogementController($pdo);
            if ($page === 'home') $controller->accueil();
            elseif ($page === 'recherche') $controller->rechercher();
            elseif ($page === 'logement-liste') $controller->list();
            break;
        
        case 'detail':  
            require_once __DIR__ . '/Controllers/LogementController.php';
            $controller = new LogementController($pdo);
            $controller->detail();
            break;

        case 'faq':
            require_once __DIR__ . '/Controllers/FaqController.php';
            $controller = new FaqController($pdo);
            $controller->showFaq();
            break;

        case 'actualite':
            require_once __DIR__ . '/Controllers/ActualiteController.php';
            $controller = new ActualiteController();

            $action = $_GET['action'] ?? 'liste';
            $id = $_GET['id'] ?? null;

            if ($action === 'detail' && $id !== null) {
                $controller->detail($id);
            } else {
                $controller->liste();
            }
            break;

        case 'contact':
            require_once __DIR__ . '/Controllers/ContactController.php';
            $controller = new ContactController($pdo);
            $controller->afficherFormulaire();
            break;

        case 'connexion':
            require_once __DIR__ . '/Controllers/ConnexionController.php';
            $controller = new ConnexionController($pdo);
            $controller->connexion();
            break;
        
        case 'deconnexion':
            require_once __DIR__ . '/Controllers/AuthController.php';
            $controller = new AuthController($pdo);
            $controller->logout();
            break;
        
        case 'inscription':
            require_once __DIR__ . '/Controllers/InscriptionController.php';
            $controller = new InscriptionController($pdo);
            $controller->inscrire();
            break;

        case 'mot-de-passe':
            require_once __DIR__ . '/Controllers/PasswordController.php';
            $controller = new PasswordController($pdo);

            switch ($action) {
                case 'forgotForm':
                    $controller->forgotForm();
                    break;
                case 'sendResetLink':
                    $controller->sendResetLink();
                    break;
                case 'resetForm':
                    $controller->resetForm();
                    break;
                case 'resetPassword':
                    $controller->resetPassword();
                    break;
                default:
                    http_response_code(404);
                    echo "Action non trouvée pour mot de passe.";
                    break;
            }
            break;

        case 'profil':
            require_once __DIR__ . '/Controllers/ProfilController.php';
            $controller = new ProfilController($pdo);
    
            $action = $_GET['action'] ?? 'show';
    
            switch ($action) {
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $controller->updateProfile();
                    } else {

                        header("Location: /index.php?page=profil");
                        exit;
                    }
                    break;
            
                case 'change-password':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $currentPass = $_POST['current_password'] ?? '';
                        $newPass = $_POST['new_password'] ?? '';
                        $confirmPass = $_POST['confirm_password'] ?? '';
                
                        $controller->changePassword($currentPass, $newPass, $confirmPass);
                    } else {
                        $controller->showPasswordForm();
                    }
                    break;
            
                case 'show':
                default:
                    if (!isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
                        header("Location: index.php?page=connexion");
                        exit;
                    }
                    $controller->showProfile();
                    break;
            }
            break;

        case 'avis':
            require_once __DIR__ . '/Controllers/AvisController.php';
            $controller = new AvisController($pdo);
            $controller->ajouterAvis();
            break;

        case 'message':
            require_once __DIR__ . '/Controllers/MessageController.php';
            $controller = new MessageController($pdo);
            $controller->envoyer();
            break;

        case 'proposer-bien':
            require_once __DIR__ . '/Controllers/ProposerBienController.php';
            $controller = new ProposerBienController($pdo);
            $controller->showForm();
            break;

        case 'admin':
            require_once __DIR__ . '/Controllers/AdminController.php';
            $controller = new AdminController($pdo);
            $controller->dashboard();
            break;

        case 'user':
            require_once __DIR__ . '/Controllers/UserController.php';
            $controller = new UserController($pdo);
            $controller->gerer();
            break;

        case 'legal':
            require_once __DIR__ . '/Controllers/Legal.php';
            $controller = new LegalController();
            $controller->afficher();
            break;
        
        case 'login':
            require_once __DIR__ . '/Controllers/AuthController.php';
            $controller = new AuthController($pdo);
            $controller->login();
            break;
        
        case 'toggle-favori':
            require_once __DIR__ . '/Controllers/FavorisController.php';
            $controller = new FavorisController($pdo);
            $controller->toggleFavori();
            break;
        
        case 'messagerie':
            if (!isset($_SESSION['user'])) {
                header('Location: index.php?page=connexion');
                exit;
            }

            require_once __DIR__ . '/Controllers/MessagerieController.php';

            $type = $_GET['type'] ?? '';
            $recipient = $_GET['recipient'] ?? '';

            $messages = []; 

            if ($type === 'admin' || $type === 'user') {

                $messagerieController = new MessagerieController($pdo);
                $messages = $messagerieController->getMessages($type, $recipient, $_SESSION['user']['id']);
            }

            if (empty($type)) {
                include __DIR__ . '/Views/choix_messagerie.php';
            } else {
                if ($type === 'admin') {
                    include __DIR__ . '/Views/messagerie.php';
                } elseif ($type === 'user') {
                    if (empty($recipient)) {
                        include __DIR__ . '/Views/recherche_utilisateur.php';
                    } else {
                        require_once __DIR__ . '/Models/User.php';
                        $userModel = new User($pdo);
                        $recipientUser = $userModel->getUserByIdentifier($recipient);

                        if ($recipientUser && $recipientUser['id'] != $_SESSION['user']['id']) {
                            include __DIR__ . '/Views/messagerie.php';
                        } else {
                            $_SESSION['error'] = "Utilisateur non trouvé ou invalide";
                            include __DIR__ . '/Views/recherche_utilisateur.php';
                        }
                    }
                } else {
                    header('Location: index.php?page=messagerie');
                    exit;
                }
            }
            break;

        default:
            http_response_code(404);
            echo "Page non trouvée.";
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Erreur base de données : " . htmlspecialchars($e->getMessage());
    // Logger en production
}
?>
