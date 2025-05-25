<?php
// controllers/UserController.php

require_once(__DIR__ . '/../mail.php');
require_once(__DIR__ . '/../Models/User.php');

class UserController {

    private $mailService;

    public function __construct() {
        $this->mailService = new MailService();
    }

    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $email = $_POST['email'];
            $username = $_POST['username'];
            // ... validation, création utilisateur en BDD via User::create() ...

            // Exemple : Générer un lien de confirmation
            $verificationLink = "https://etulogis.herogu.garageisep.com/verification.php?token=un_token_unique";

            // Envoie du mail de confirmation
            $this->mailService->sendConfirmationEmail($email, $username, $verificationLink);

            // Affiche la vue de confirmation
            include '../views/user/confirmation_inscription.php';
        } else {
            include '../views/user/inscription.php';
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            // Vérifier que l'email existe, générer un token, etc.

            $resetLink = "https://etulogis.herogu.garageisep.com/reset_password.php?token=token_reset";

            $this->mailService->sendResetEmail($email, $email, $resetLink);

            include '../views/user/mail_reset_envoye.php';
        } else {
            include '../views/user/reset_password.php';
        }
    }
}
