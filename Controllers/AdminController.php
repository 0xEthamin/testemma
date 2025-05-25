<?php

declare(strict_types=1);

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Models/User.php');
require_once(__DIR__ . '/../Models/Message.php');
require_once(__DIR__ . '/../Models/UserModel.php');
require_once(__DIR__ . '/../Models/LogementModel.php');

class AdminController {
    private $pdo;
    private $userModel;
    private $logementModel;
    private const ADMIN_ID = 12; 

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
        $this->logementModel = new LogementModel($pdo);
        
        $this->checkAdminAccess();
    }

    private function checkAdminAccess(): void {
        if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
            header("Location: login.php");
            exit;
        }
    }

    public function dashboard(): void {
        try {
            $stmt = $this->pdo->prepare("
                SELECT DISTINCT u.id, u.username 
                FROM users u
                JOIN internal_messages m 
                  ON (u.id = m.user_id AND m.recipient_id = :adminId)
                  OR (u.id = m.recipient_id AND m.user_id = :adminId)
                ORDER BY m.sent_at DESC
            ");
            $stmt->execute(['adminId' => self::ADMIN_ID]);
            $userMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = [
                'usersNonVerified' => User::getUnverifiedUsers($this->pdo),
                'usersVerified' => User::getVerifiedUsers($this->pdo),
                'messages' => Message::getAllMessages($this->pdo),
                'logementsNonValides' => $this->logementModel->getLogementsNonValidés(),
                'userMessages' => $userMessages,
            ];
            
            $this->renderView('admin', $data);

        } catch (Exception $e) {
            error_log("Admin dashboard error: " . $e->getMessage());
            $this->renderView('error', ['message' => 'Erreur lors du chargement du tableau de bord']);
        }
    }

    private function renderView(string $viewName, array $data = []): void {
        extract($data);
        require_once __DIR__ . '/../Views/' . $viewName . '.php';
    }
}
