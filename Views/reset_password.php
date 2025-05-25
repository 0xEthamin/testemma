<?php
include __DIR__ . '/../header.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Réinitialisation du mot de passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css">
  <link rel="stylesheet" href="/Web-Mimba/styles/reset.css">
</head>
<body>
<main>
    <div class="container">
        <h2>Réinitialiser votre mot de passe</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" action="/Web-Mimba/index.php?page=mot-de-passe&action=resetPassword" novalidate>
            <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
            
            <label for="password">Nouveau mot de passe :</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <div id="password-requirements">
                <ul>
                    <li id="length" style="color:red;">Au moins 8 caractères</li>
                    <li id="lower" style="color:red;">Une minuscule</li>
                    <li id="upper" style="color:red;">Une majuscule</li>
                    <li id="number" style="color:red;">Un chiffre</li>
                    <li id="special" style="color:red;">Un caractère spécial</li>
                    <li id="match" style="color:red;">Les mots de passe doivent correspondre</li>
                </ul>
            </div>

            <button type="submit">Changer le mot de passe</button>
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

