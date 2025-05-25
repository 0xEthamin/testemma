<?php
class User {
    private PDO $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public static function setConnection($pdo) {
        self::$pdo = $pdo;
    }
    private static function executeQuery($pdo, $sql, $params = []) {
        try {
            $stmt = $pdo->prepare($sql);  
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            return false;
        }
    }

    public static function getUnverifiedUsers($pdo) {
        $stmt = self::executeQuery($pdo, 
            "SELECT id, username, email, created_at 
             FROM users 
             WHERE is_verified = 0");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public static function getVerifiedUsers($pdo) {
        $stmt = self::executeQuery($pdo, 
            "SELECT id, username, email, created_at 
             FROM users 
             WHERE is_verified = 1");
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public static function findByEmail($pdo, $email) {
        $stmt = self::executeQuery($pdo, 
            "SELECT * FROM users 
             WHERE email = ?", 
            [$email]);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public static function setResetToken($pdo, $userId, $token, $expires) {
        return self::executeQuery($pdo, 
            "UPDATE users 
             SET reset_token = ?, reset_expires = ? 
             WHERE id = ?", 
            [$token, $expires, $userId]);
    }

    public static function findByUsername($pdo, $username) {
        $stmt = self::executeQuery($pdo, 
            "SELECT * FROM users 
             WHERE username = ?", 
            [$username]);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public static function updateProfile($pdo, $userId, $name, $email, $phone) {
        return self::executeQuery($pdo, 
            "UPDATE users 
             SET name = ?, email = ?, phone = ? 
             WHERE id = ?", 
            [$name, $email, $phone, $userId]);
    }

    public static function findByResetToken($pdo, $token) {
        $stmt = self::executeQuery($pdo, 
            "SELECT * FROM users 
             WHERE reset_token = ? 
             AND reset_expires > NOW()", 
            [$token]);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public static function updatePassword($pdo, $userId, $hashedPassword) {
        return self::executeQuery($pdo, 
            "UPDATE users 
             SET password = ? 
             WHERE id = ?", 
            [$hashedPassword, $userId]);
    }

    public static function clearResetToken($pdo, $userId) {
        return self::executeQuery($pdo, 
            "UPDATE users 
             SET reset_token = NULL, reset_expires = NULL 
             WHERE id = ?", 
            [$userId]);
    }

    public static function findById($pdo, $id) {
        $stmt = self::executeQuery($pdo, "SELECT * FROM users WHERE id = ?", [$id]);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public static function verifyCredentials($pdo, $email, $password) {
        $user = self::findByEmail($pdo, $email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public static function create($pdo, $username, $email, $hashedPassword) {
        return self::executeQuery($pdo,
            "INSERT INTO users (username, email, password, created_at) 
             VALUES (?, ?, ?, NOW())",
            [$username, $email, $hashedPassword]);
    }

    public function getUserByIdentifier($identifier) {
        $stmt = $this->pdo->prepare("
            SELECT id, username, email 
            FROM users 
            WHERE username = ? OR email = ?
            LIMIT 1
        ");
        $stmt->execute([$identifier, $identifier]);
        return $stmt->fetch();
    }

    public function searchUsers($searchTerm, $excludeUserId) {
        $stmt = $this->pdo->prepare("
            SELECT id, username, email 
            FROM users 
            WHERE (username LIKE ? OR email LIKE ?)
            AND id != ?
            LIMIT 10
        ");
        $searchTerm = "%{$searchTerm}%";
        $stmt->execute([$searchTerm, $searchTerm, $excludeUserId]);
        return $stmt->fetchAll();
    }
}