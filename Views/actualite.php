<!-- Views/actualite.php -->
<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Actualités</title>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css">
  <link rel="stylesheet" href="/Web-Mimba/styles/actualite.css">
</head>

<body>
  <main class="articles-container">
    <h1>Nos dernières actualités</h1>
    
    <?php foreach ($articles as $article): ?>
      <div class="article-preview">
        <h2><?= htmlspecialchars($article['titre']) ?></h2>
        <p><?= htmlspecialchars($article['accroche']) ?></p>
        <a href="index.php?page=actualite&action=detail&id=<?= $article['id'] ?>" class="read-more">Lire la suite →</a>
      </div>
    <?php endforeach; ?>
  </main>

  <?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
