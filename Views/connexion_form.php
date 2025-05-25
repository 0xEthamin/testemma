<?php $error = $error ?? ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css">
  <link rel="stylesheet" href="/Web-Mimba/styles/connexion.css">
</head>
<body>
  <?php include __DIR__ . '/../header.php'; ?>

  <main>
    <div class="container">
      <h2>Se connecter</h2>

      <?php if (!empty($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="index.php?page=connexion" method="post">
        <div class="form-group">
          <label for="email">Adresse email</label>
          <input type="email" id="email" name="email" placeholder="Adresse email" required>
        </div>
        
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Mot de passe" required>
        </div>
        
        <button type="submit" class="btn">Connexion</button>
      </form>

      <div class="links">
        <a href="index.php?page=mot-de-passe&action=forgotForm">Mot de passe oublié ?</a>
        <p>Pas encore de compte ? <a href="index.php?page=inscription">Créer un compte</a></p>
      </div>
    </div>
  </main>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
