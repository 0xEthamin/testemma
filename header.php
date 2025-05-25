<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = '';

$isLoggedIn = isset($_SESSION['user']); 
$name = $isLoggedIn ? htmlspecialchars($_SESSION['user']['name'] ?? '', ENT_QUOTES, 'UTF-8') : '';
$isAdmin = $isLoggedIn && ($_SESSION['user']['is_admin'] ?? false);
?>

<header class="header">
  <div class="logo">
    <a href="<?= $base_url ?>/index.php" aria-label="Retour à l'accueil">
      <img src="<?= $base_url ?>/assets/img/logov3.png" alt="Logo Etulogis" loading="lazy">
    </a>
  </div>

  <nav class="nav" aria-label="Navigation principale">
    <a href="<?= $base_url ?>/index.php?page=logement-liste">LOGEMENTS</a>
    <a href="<?= $base_url ?>/index.php?page=proposer-bien">PROPOSER UN BIEN</a>
    <a href="<?= $base_url ?>/index.php?page=actualite">DÉCOUVRIR</a>
    <a href="<?= $base_url ?>/index.php?page=contact">CONTACT</a>

    <?php if ($isLoggedIn): ?>
      <div class="user-menu">
        <button class="user-toggle" aria-expanded="false" aria-controls="user-dropdown">
          <?= strtoupper($name) ?>
          <span class="dropdown-arrow">▼</span>
        </button>
        <div class="user-dropdown" id="user-dropdown">
          <a href="<?= $base_url ?>/index.php?page=profil">MON PROFIL</a>
          <a href="<?= $base_url ?>/index.php?page=messagerie">MESSAGERIE</a>
          <?php if ($isAdmin): ?>
            <a href="<?= $base_url ?>/index.php?page=admin">PANNEAU ADMINISTRATEUR</a>
          <?php endif; ?>
          <a href="<?= $base_url ?>/index.php?page=deconnexion">DÉCONNEXION</a>
        </div>
      </div>
    <?php else: ?>
      <a href="<?= $base_url ?>/index.php?page=connexion" class="login-btn">CONNEXION</a>
    <?php endif; ?>
  </nav>
  
  <button class="mobile-menu-toggle" aria-label="Menu mobile">
    <span></span>
    <span></span>
    <span></span>
  </button>
</header>