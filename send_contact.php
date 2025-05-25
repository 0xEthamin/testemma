<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../Views/contact_form.php?error=1");
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $message]);

        header("Location: ../Views/contact_form.php?success=1");
        exit;
    } catch (PDOException $e) {
        // Loguer l'erreur en interne et rediriger sans exposer le message exact
        error_log("Erreur d'insertion message de contact : " . $e->getMessage());
        header("Location: ../Views/contact_form.php?error=1");
        exit;
    }
}
?>
