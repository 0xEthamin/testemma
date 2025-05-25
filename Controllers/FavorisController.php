<?php
class FavorisController {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function toggleFavori() {
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $logementId = $data['logement_id'] ?? null;
        $action = $data['action'] ?? null;
        $userId = $_SESSION['user']['id'];

        if (!$logementId || !in_array($action, ['add', 'remove'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Requête invalide']);
            exit;
        }

        try {
            if ($action === 'add') {
                $stmt = $this->pdo->prepare("INSERT IGNORE INTO favoris (user_id, logement_id) VALUES (?, ?)");
                $stmt->execute([$userId, $logementId]);
            } else {
                $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE user_id = ? AND logement_id = ?");
                $stmt->execute([$userId, $logementId]);
            }

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur serveur']);
        }
    }


    public function getFavoris($userId) {
        $stmt = $this->pdo->prepare("
            SELECT l.* FROM logements l
            JOIN favoris f ON l.id = f.logement_id
            WHERE f.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
