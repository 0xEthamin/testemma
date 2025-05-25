<?php
class FavorisModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getFavorisByUser(int $userId): array {
        $stmt = $this->pdo->prepare("SELECT logement_id FROM favoris WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function isFavori(int $userId, int $logementId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM favoris WHERE user_id = ? AND logement_id = ?");
        $stmt->execute([$userId, $logementId]);
        return (bool)$stmt->fetchColumn();
    }

    public function addFavori(int $userId, int $logementId): bool {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO favoris (user_id, logement_id) VALUES (?, ?)");
        return $stmt->execute([$userId, $logementId]);
    }

    public function removeFavori(int $userId, int $logementId): bool {
        $stmt = $this->pdo->prepare("DELETE FROM favoris WHERE user_id = ? AND logement_id = ?");
        return $stmt->execute([$userId, $logementId]);
    }
}
