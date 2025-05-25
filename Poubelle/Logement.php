<?php
class Logement {
    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM logements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
