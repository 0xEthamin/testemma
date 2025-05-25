<?php 
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../config.php';

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /index.php?page=connexion');
    exit;
}

// Récupère les infos de l'utilisateur connecté
$user = $_SESSION['user'];

// Récupération des favoris
require_once __DIR__ . '/../Controllers/FavorisController.php';
$favorisController = new FavorisController($pdo);
$favoris = $favorisController->getFavoris($user['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Utilisateur</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/profil.css">
</head>
<body>

<main>
  <div class="container">
    <!-- Carte Profil -->
    <div class="card">
      <h2>Informations</h2>
      <ul class="profile-info">
        <li><strong>Nom :</strong> <?= htmlspecialchars($user['name']) ?></li>
        <li><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></li>
        <li><strong>Téléphone :</strong> <?= htmlspecialchars($user['phone']) ?></li>
      </ul>
      <button class="btn btn-primary" onclick="openModal()">Modifier</button>
      <div class="pdf-section">
        <p><strong>Document :</strong> Profil_complet.pdf</p>
        <button class="btn btn-danger">Supprimer</button>
      </div>
    </div>

    <!-- Critères -->
    <div class="card">
      <h2>Critères de recherche</h2>
      <label>Localisation :
        <select>
          <option>Choisir</option>
          <option>Paris</option>
          <option>Lyon</option>
          <option>Marseille</option>
        </select>
      </label>
      <label>DPE :
        <select>
          <option>Choisir</option>
          <option>A</option>
          <option>B</option>
          <option>C</option>
          <option>D</option>
        </select>
      </label>
      <label>Prix :
        <select>
          <option>-600€</option>
          <option>600-800€</option>
          <option>800-1000€</option>
          <option>1000€+</option>
        </select>
      </label>
      <button class="btn btn-secondary">Réinitialiser</button>
    </div>

    <!-- Espace perso avec onglets -->
    <div class="card">
      <h2>Mon Espace</h2>
      <div class="tabs">
        <div class="tab-btn active" onclick="showTab(0)">Favoris</div>
        <div class="tab-btn" onclick="showTab(1)">Demandes</div>
        <div class="tab-btn" onclick="showTab(2)">Alertes</div>
      </div>

      <!-- Contenu des onglets -->
      <div class="tab-content active">
        <?php if (!empty($favoris)): ?>
          <div class="favoris-list">
            <?php foreach ($favoris as $logement): ?>
              <div class="favori-item">
                <h3><?= htmlspecialchars($logement['Nom']) ?></h3>
                <p><?= htmlspecialchars($logement['Zone']) ?> - <?= htmlspecialchars($logement['regions']) ?></p>
                <a href="/index.php?page=detail&id=<?= $logement['id'] ?>">Voir le logement</a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p>⭐ Aucun favori pour le moment.</p>
        <?php endif; ?>
      </div>
      
      <div class="tab-content">
        <p>📨 Vous n'avez pas encore fait de demande.</p>
      </div>
      
      <div class="tab-content">
        <p>🔔 Aucune alerte active.</p>
      </div>
    </div>
  </div>

  <!-- MODAL -->
  <div class="modal" id="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeModal()">✖</span>
      <h2>Modifier mes infos</h2>
      <form method="POST" action="index.php?controller=profile&action=updateProfile">
        <input type="text" name="name" placeholder="Nom" value="<?= htmlspecialchars($user['name']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
        <input type="tel" name="phone" placeholder="Téléphone" value="<?= htmlspecialchars($user['phone']) ?>" required>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </form>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../footer.php'; ?>

<script>
  function showTab(index) {
    document.querySelectorAll('.tab-btn').forEach((btn, i) => {
      btn.classList.toggle('active', i === index);
    });
    document.querySelectorAll('.tab-content').forEach((tab, i) => {
      tab.classList.toggle('active', i === index);
    });
  }

  function openModal() {
    document.getElementById('modal').classList.add('show');
  }

  function closeModal() {
    document.getElementById('modal').classList.remove('show');
  }
</script>
</body>
</html>