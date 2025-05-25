<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Messagerie interne</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/recherche_utilisateur.css">
</head>
<body>
<div class="messagerie-container">
    <div class="user-search-container">
        <h1>Contacter un colocataire</h1>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert error">
                <?= htmlspecialchars($_SESSION['error_message'], ENT_QUOTES, 'UTF-8') ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <form action="/index.php?page=messagerie" method="get" class="user-search-form">
            <input type="hidden" name="page" value="messagerie">
            <input type="hidden" name="type" value="user">
            
            <div class="form-group">
                <label for="recipient-search">Rechercher un utilisateur :</label>
                <input type="text" 
                       id="recipient-search" 
                       name="recipient" 
                       placeholder="Entrez un nom d'utilisateur ou email"
                       required
                       class="search-input">
                <button type="submit" class="search-button">Rechercher</button>
            </div>
        </form>
        
        <?php if (!empty($searchResults)): ?>
            <div class="search-results">
                <h2>Résultats de la recherche</h2>
                
                <?php foreach ($searchResults as $user): ?>
                    <div class="user-result">
                        <div class="user-info">
                            <span class="username"><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="email"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <a href="/index.php?page=messagerie&type=user&recipient=<?= urlencode($user['username']) ?>" 
                           class="contact-button">
                            Contacter
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
            
<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>