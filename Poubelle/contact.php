<?php include 'header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="Style.css">
  <link rel="stylesheet" href="Contact.css">
</head>
<body>
  <main class="main-content">
    <h1>Contactez-nous</h1>

    <div class="container">
      <h2>Formulaire de contact</h2>
      <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Votre message a été envoyé avec succès.</p>
      <?php endif; ?>

      <form action="send_contact.php" method="POST">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <button type="submit">Envoyer</button>
      </form>
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
