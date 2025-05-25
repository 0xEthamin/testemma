<?php
require 'config.php';
require 'mail.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));
    $expire = date("Y-m-d H:i:s", strtotime("+1 hour"));

    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?");
    $stmt->execute([$token, $expire, $email]);

    $link = "http://localhost/reset_password.php?token=$token";
    $message = "Cliquez ici pour réinitialiser votre mot de passe : <a href='$link'>Réinitialiser</a>";

    sendMail($email, "Reinitialisation de mot de passe", $message);

    echo "Un email de réinitialisation a été envoyé.";
}
?>
