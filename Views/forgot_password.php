<?php
include __DIR__ . '/../header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Réinitialisation de mot de passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../Web-Mimba/styles/style.css">
  <link rel="stylesheet" href="../Web-Mimba/styles/reset.css">
</head>
<body>
<main>
    <div class="container">
        <h2>Mot de passe oublié</h2>

        <?php if (!empty($success)): ?>
            <div class="message"><?= htmlspecialchars($success) ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/Web-Mimba/index.php?page=mot-de-passe&action=sendResetLink">
            <input type="email" name="email" placeholder="Votre email" required>
            <button type="submit">Envoyer le lien</button>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
</body>