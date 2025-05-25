<?php
class FaqModel {
    public function getFaqItems(): array {
        return [
            [
                'question' => "Comment créer un compte sur Etulogis ?",
                'answer' => 'Rendez-vous sur la <a href="Views/creation_compte.php">page d\'inscription</a> et remplissez le formulaire avec vos informations étudiantes.'
            ],
            [
                'question' => "J’ai oublié mon mot de passe, que faire ?",
                'answer' => 'Cliquez sur “Mot de passe oublié” sur la page de connexion et suivez les instructions pour réinitialiser votre mot de passe.'
            ],
            [
                'question' => "Comment postuler à une offre de logement ?",
                'answer' => '<a href="Views/connexion_form.php">Connectez-vous</a> à votre compte, explorez les offres disponibles et cliquez sur “Postuler”.'
            ],
            [
                'question' => "Quels documents dois-je fournir ?",
                'answer' => 'L\'ensemble des documents à fournir se trouve dans votre page <a href="Views/profil.php">Profil</a>.'
            ],
            [
                'question' => "Puis-je visiter le logement avant de réserver ?",
                'answer' => 'Oui, certaines offres permettent la visite. Vérifiez les modalités indiquées dans l’annonce ou contactez le propriétaire.'
            ],
            [
                'question' => "Y a-t-il des aides financières pour le logement étudiant ?",
                'answer' => 'Oui, vous pouvez faire une demande d’APL ou consulter le CROUS. Des aides locales sont parfois disponibles.'
            ],
            [
                'question' => "Puis-je modifier ou annuler ma réservation ?",
                'answer' => 'Oui, vous pouvez modifier ou annuler un créneau tant qu’il n’est pas passé. Allez dans “Mes Réservations”.'
            ],
            [
                'question' => "Mes données sont-elles sécurisées ?",
                'answer' => 'Oui, nous utilisons des protocoles de sécurité pour protéger vos données personnelles et confidentielles. Pour en savoir plus consultez <a href="CGU_Mention_Legal.php">les CGU</a>.'
            ],
            [
                'question' => "Comment contacter le support ?",
                'answer' => 'Vous pouvez nous écrire à <a href="mailto:support@etulogis.com">support@etulogis.com</a> ou via le <a href="Views/contact_form.php">formulaire de contact</a>.'
            ],
            [
                'question' => "Où signaler un bug ou un problème technique ?",
                'answer' => 'Vous pouvez utiliser le <a href="Views/contact_form.php">formulaire de contact</a> ou nous envoyer un email avec une capture d\'écran du problème rencontré.'
            ],
        ];
    }
}
