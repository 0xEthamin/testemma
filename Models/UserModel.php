<?php
class UserModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }

    public function usernameExists(string $username): bool {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return (bool)$stmt->fetch();
    }

    public function createUser(string $username, string $firstname, string $name, string $email, string $phone, string $hashedPassword, string $token): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (username, firstname, name, email, phone, password, verification_token, is_verified)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ");

        try {
            return $stmt->execute([$username, $firstname, $name, $email, $phone, $hashedPassword, $token]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }

    public function deleteById(int $id): void {
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

}
