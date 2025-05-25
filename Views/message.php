<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/message.css">
</head>
<body>
  <main class="main-content">
    <h1>Contacter le propriétaire</h1>

    <div class="container">
      <h2>Formulaire de contact</h2>
      <form>
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

  <?php include __DIR__ . '/../footer.php'; ?>

</body>
</html>
