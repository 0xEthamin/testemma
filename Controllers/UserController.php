<?php

require_once(__DIR__ . '/../mail.php');
require_once(__DIR__ . '/../Models/User.php');

class UserController {

    private $mailService;

    public function __construct() {
        $this->mailService = new MailService();
    }

    public function inscription() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $verificationLink = "https://etulogis.herogu.garageisep.com/verification.php?token=un_token_unique";

            $this->mailService->sendConfirmationEmail($email, $username, $verificationLink);

            include '../views/user/confirmation_inscription.php';
        } else {
            include '../views/user/inscription.php';
        }
    }

    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];

            $resetLink = "https://etulogis.herogu.garageisep.com/reset_password.php?token=token_reset";

            $this->mailService->sendResetEmail($email, $email, $resetLink);

            include '../views/user/mail_reset_envoye.php';
        } else {
            include '../views/user/reset_password.php';
        }
    }
}
