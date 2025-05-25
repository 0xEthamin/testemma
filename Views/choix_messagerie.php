<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Messagerie interne</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/messagerie.css">
</head>
<body>
<div class="messagerie-choice-container">
  <h1>Choisir le type de conversation</h1>
  
  <div class="choice-buttons">
    <a href="index.php?page=messagerie&type=admin" class="choice-button">Contacter un administrateur</a>
    <a href="index.php?page=messagerie&type=user" class="choice-button">Contacter un colocataire</a>
  </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>