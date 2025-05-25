<?php include __DIR__ . '/../header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contact</title>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css">
  <link rel="stylesheet" href="/Web-Mimba/styles/contact.css">
</head>
<body>
  <main class="main-content">
    <h1>Contactez-nous</h1>

    <div class="container">
      <h2>Formulaire de contact</h2>
      
      <?php if ($success): ?>
        <div class="alert success">
          <p>Votre message a été envoyé avec succès.</p>
        </div>
      <?php elseif ($error): ?>
        <div class="alert error">
          <p>Erreur lors de l'envoi. Veuillez réessayer.</p>
        </div>
      <?php endif; ?>

      <form method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

        <button type="submit">Envoyer</button>
      </form>
    </div>
  </main>

  <?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>