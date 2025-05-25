<!-- Views/article_detail.php -->
<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($article['titre']) ?></title>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/article_detail.css">
</head>

<body>
  <main class="article-container">
    <div class="article-full">
      <h1><?= htmlspecialchars($article['titre']) ?></h1>
      <div>
        <?= nl2br(htmlspecialchars($article['contenu'])) ?>
      </div>
      <a href="index.php?page=actualite" class="back-link">← Retour aux actualités</a>
    </div>
  </main>

  <?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
