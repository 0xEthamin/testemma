<?php
// services/MailService.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

class MailService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
    }

    private function setupSMTP() {
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'emma.appg9c@gmail.com';
        $this->mail->Password   = 'uidb psoe gmyy rfdw'; // mot de passe d’application
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port       = 587;
        $this->mail->setFrom('emma.appg9c@gmail.com', 'Etulogis');
    }

    public function sendConfirmationEmail($to, $username, $verificationLink) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to, $username);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Confirmation de votre inscription';
            $this->mail->Body    = "Bonjour <strong>$username</strong>,<br><br>
                Merci pour votre inscription.<br>
                Cliquez sur le lien ci-dessous pour activer votre compte :<br>
                <a href='$verificationLink'>$verificationLink</a>";
            $this->mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail de confirmation : {$this->mail->ErrorInfo}");
        }
    }

    public function sendResetEmail($to, $username, $resetLink) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to, $username);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Réinitialisation de mot de passe';
            $this->mail->Body    = "Bonjour <strong>$username</strong>,<br><br>
                Cliquez sur le lien suivant pour réinitialiser votre mot de passe :<br>
                <a href='$resetLink'>$resetLink</a><br><br>
                Ce lien expirera dans une heure.";
            $this->mail->send();
        } catch (Exception $e) {
            error_log("Erreur lors de l'envoi du mail de réinitialisation : {$this->mail->ErrorInfo}");
        }
    }

    public function sendCustomEmail($to, $toName, $subject, $body) {
    try {
        $this->mail->clearAddresses();
        $this->mail->addAddress($to, $toName);
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->send();
    } catch (Exception $e) {
        error_log("Erreur envoi mail personnalisé : " . $this->mail->ErrorInfo);
        throw $e;
    }
}

}
