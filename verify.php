<?php
require 'config.php';
include __DIR__ . '/header.php';

$message = '';
$title = 'Vérification du compte';

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        $update->execute([$user['id']]);

        if ($update->rowCount() > 0) {
            $message = "<h1>✅ Compte activé !</h1><p>Votre compte est maintenant actif. Vous pouvez vous connecter.</p><a href='Views/connexion_form.php' class='button'>Se connecter</a>";
        } else {
            $message = "<h1>❌ Oups...</h1><p>Une erreur est survenue lors de l'activation. Veuillez réessayer.</p>";
        }
    } else {
        $message = "<h1>Token invalide</h1><p>Ce lien de vérification est invalide ou a déjà été utilisé.</p>";
    }
} else {
    $message = "<h1>🔗 Token manquant</h1><p>Le lien de vérification est incomplet.</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/verify.css">
</head>
<body>

<main>
  <div class="container">
    <?= $message ?>
  </div>
</main>

<footer>
  <div class="footer-links">
    <a href="FAQ.php">FAQ</a>
    <a href="CGU_Mention_Legal.php">CGU et Mentions Légales</a>
  </div>
  <h4>Réseaux sociaux</h4>
</footer>

</body>
</html>
