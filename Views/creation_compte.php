<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Controllers/InscriptionController.php');

include __DIR__ . '/../header.php';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Inscription</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/creation_compte.css">
</head>
<body>
<main>
  <div class="container">
    <h2>Créer un compte</h2>

    <?php if (!empty($message)): ?>
      <div class="alert <?= $isSuccess ? 'alert-success' : 'alert-error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form action="" method="post" novalidate>
      <label>Civilité :</label>
      <label><input type="radio" name="civility" value="Monsieur" required> Monsieur</label>
      <label><input type="radio" name="civility" value="Madame" required> Madame</label>

      <label for="firstname">Prénom :</label>
      <input type="text" id="firstname" name="firstname" required>

      <label for="name">Nom :</label>
      <input type="text" id="name" name="name" required>

      <label for="username">Nom d'utilisateur :</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Adresse email :</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Numéro de téléphone :</label>
      <input type="tel" id="phone" name="phone" required>

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirmer le mot de passe :</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <div id="password-requirements" style="font-size: 0.9rem; color: #333; margin-bottom: 10px;">
        <ul>
          <li id="length" style="color:red;">Au moins 8 caractères</li>
          <li id="lower" style="color:red;">Une minuscule</li>
          <li id="upper" style="color:red;">Une majuscule</li>
          <li id="number" style="color:red;">Un chiffre</li>
          <li id="special" style="color:red;">Un caractère spécial</li>
          <li id="match" style="color:red;">Les mots de passe doivent correspondre</li>
        </ul>
      </div>

      <label>
        <input type="checkbox" name="cgu" required>
        J'accepte les <a href="legal.php" target="_blank">Conditions Générales d'Utilisation</a>
      </label>

      <button type="submit">S'inscrire</button>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm_password');

  const length = document.getElementById('length');
  const lower = document.getElementById('lower');
  const upper = document.getElementById('upper');
  const number = document.getElementById('number');
  const special = document.getElementById('special');
  const match = document.getElementById('match');

  function validate() {
    const pwd = password.value;
    const confirmPwd = confirm.value;

    length.style.color = pwd.length >= 8 ? 'green' : 'red';
    lower.style.color = /[a-z]/.test(pwd) ? 'green' : 'red';
    upper.style.color = /[A-Z]/.test(pwd) ? 'green' : 'red';
    number.style.color = /\d/.test(pwd) ? 'green' : 'red';
    special.style.color = /[^a-zA-Z0-9]/.test(pwd) ? 'green' : 'red';
    match.style.color = pwd && confirmPwd && pwd === confirmPwd ? 'green' : 'red';
  }

  password.addEventListener('input', validate);
  confirm.addEventListener('input', validate);
});
</script>

</body>
</html>
