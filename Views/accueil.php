<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/accueil.css">
  <title>Etulogis - Trouve ton logement</title>
</head>

<body>
  <section class="hero">
    <div class="hero-content">
      <h1>Trouve ton logement étudiant en quelques clics !</h1>
      <p>Recherche simple, rapide, efficace.</p>
      <div class="search-box">
        <input type="text" id="ville" placeholder="Ville (ex: Lyon)" />
        <input type="number" id="budget" placeholder="Budget max (€)" min="0" />
        <button onclick="lancerRecherche()">Rechercher</button>
      </div>
    </div>
  </section>

  <section class="features">
    <div class="feature">
      <h3>Inscription Rapide</h3>
      <p>Crée ton compte en quelques secondes et accède à des milliers d'annonces.</p>
    </div>
    <div class="feature">
      <h3>Filtres Avancés</h3>
      <p>Affinez vos recherches avec des filtres précis pour trouver le logement idéal.</p>
    </div>
    <div class="feature">
      <h3>Support 24/7</h3>
      <p>Notre équipe est disponible à tout moment pour répondre à vos questions.</p>
    </div>
  </section>

  <section class="cta">
    <div class="cta-box">
      <h2>Prêt à trouver ton futur chez-toi ?</h2>
      <p>Rejoins notre communauté et découvre des offres exclusives adaptées à tes besoins.</p>
      <button onclick="location.href='../Views/creation_compte.php'">Créer mon compte</button>
    </div>
  </section>

  <?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
