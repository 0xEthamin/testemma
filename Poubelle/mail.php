<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

function sendConfirmationEmail($to, $username, $verificationLink) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emma.appg9c@gmail.com';
        $mail->Password   = 'uidb psoe gmyy rfdw'; // mot de passe d’application
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('emma.appg9c@gmail.com', 'Etulogis');
        $mail->addAddress($to, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de votre inscription';
        $mail->Body    = "Bonjour <strong>$username</strong>,<br><br>
        Merci pour votre inscription.<br>
        Cliquez sur le lien ci-dessous pour activer votre compte :<br>
        <a href='$verificationLink'>$verificationLink</a>";

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur lors de l'envoi du mail : {$mail->ErrorInfo}");
    }
}


function sendResetEmail($to, $username, $resetLink) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emma.appg9c@gmail.com';
        $mail->Password   = 'uidb psoe gmyy rfdw';
        $mail->SMTPSecure = 'uidb psoe gmyy rfdw';
        $mail->Port       = 587;

        $mail->setFrom('emma.appg9c@gmail.com', 'Etulogis');
        $mail->addAddress($to, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de mot de passe';
        $mail->Body    = "Bonjour <strong>$username</strong>,<br><br>
        Cliquez sur le lien suivant pour réinitialiser votre mot de passe :<br>
        <a href='$resetLink'>$resetLink</a><br><br>
        Ce lien expirera dans une heure.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Erreur mail reset : {$mail->ErrorInfo}");
    }
}
