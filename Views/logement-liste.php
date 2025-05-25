<?php include __DIR__ . '/../header.php'; ?>

<?php
$logements = $logements ?? [];
$zones = $zones ?? [];
$regions = $regions ?? [];
$filters = $filters ?? ['zone' => '', 'region' => '', 'search' => '', 'budget' => '', 'sort' => 'name_asc'];
$pageActuelle = $pageActuelle ?? 1;
$totalPages = $totalPages ?? 1;
$queryString = $queryString ?? '';

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user']);
$userId = $isLoggedIn ? $_SESSION['user']['id'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Liste de logements</title>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css" />
  <link rel="stylesheet" href="/Web-Mimba/styles/logement.css" />
</head>
<body>
<main>
    <h1>Nos Logements Disponibles</h1>

    <!-- FILTRES -->
    <form method="get" action="index.php" class="logement-filter-form">

        <!-- Première ligne : Zone, Région, Rechercher, Budget -->
        <div class="filter-line">
          <select name="zone" >
              <option value="">-- Zone --</option>
              <?php foreach ($zones as $zone): ?>
                  <option value="<?= htmlspecialchars($zone) ?>" <?= ($filters['zone'] === $zone) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($zone) ?>
                  </option>
              <?php endforeach; ?>
          </select>

          <select name="region" >
              <option value="">-- Région --</option>
              <?php foreach ($regions as $region): ?>
                  <option value="<?= htmlspecialchars($region) ?>" <?= ($filters['region'] === $region) ? 'selected' : '' ?>>
                      <?= htmlspecialchars($region) ?>
                  </option>
              <?php endforeach; ?>
          </select>

          <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($filters['search']) ?>" />

          <input type="number" name="budget" placeholder="Budget max (€)" min="0" value="<?= htmlspecialchars($filters['budget']) ?>" />
        </div>

        <!-- Deuxième ligne : Trier par, menu déroulant, bouton Filtrer -->
        <div class="sort-line">
          <label for="sort">Trier par :</label>
          <select name="sort" id="sort" onchange="this.form.submit()">
              <option value="name_asc" <?= ($filters['sort'] ?? 'name_asc') === 'name_asc' ? 'selected' : '' ?>>Nom (A-Z)</option>
              <option value="name_desc" <?= ($filters['sort'] ?? '') === 'name_desc' ? 'selected' : '' ?>>Nom (Z-A)</option>
              <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Prix (croissant)</option>
              <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Prix (décroissant)</option>
          </select>

          <button type="submit">Filtrer</button>
        </div>

        <input type="hidden" name="page" value="logement-liste" />
    </form>

    <!-- LOGEMENTS -->
    <div class="container-logements" style="margin-top: 20px;">
        <?php if (count($logements) > 0): ?>
            <?php foreach ($logements as $logement): ?>
                <div class="logement">
                    <?php
                    $photo = trim($logement['photo'] ?? '');
                    if ($photo !== '' && filter_var($photo, FILTER_VALIDATE_URL)):
                    ?>
                        <img src="<?= htmlspecialchars($photo) ?>" alt="" />
                    <?php endif; ?>
                    <div>
                        <h2><a href="/Web-Mimba/index.php?page=detail&id=<?= $logement['id'] ?>"><?= htmlspecialchars($logement['Nom']) ?></a></h2>
                        <p><strong>Zone :</strong> <?= htmlspecialchars($logement['Zone']) ?></p>
                        <p><strong>Région :</strong> <?= htmlspecialchars($logement['regions']) ?></p>
                        <?php
                        $prixArray = explode(',', trim($logement['prix'], '{}'));
                        $prixArray = array_map('floatval', $prixArray);
                        $prixArray = array_filter($prixArray, fn($p) => $p > 0);
                        if (!empty($prixArray)) {
                            $minPrix = min($prixArray);
                            $maxPrix = max($prixArray);
                            $affichagePrix = ($minPrix == $maxPrix)
                            ? "{$minPrix} €"
                            : "de {$minPrix} € à {$maxPrix} €";
                        } else {
                            $affichagePrix = "Non renseigné";
                        }
                        ?>
                        <p><strong>Prix :</strong> <?= $affichagePrix ?></p>
                        <p><strong>Adresse :</strong> <?= isset($logement['address']) ? htmlspecialchars($logement['address']) : 'Non renseignée' ?></p>
                    </div>
                    <?php if ($isLoggedIn): ?>
                        <div class="favori-icon-container">
                            <div class="favori-icon" data-id="<?= $logement['id'] ?>" onclick="toggleFavori(this)">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="favori-svg <?= in_array($logement['id'], $favoris) ? 'active' : '' ?>">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09 C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun logement trouvé avec ces critères.</p>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($pageActuelle > 1): ?>
            <a href="?page=logement-liste&pageNum=<?= ($pageActuelle - 1) . $queryString ?>" class="prev">Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=logement-liste&pageNum=<?= $i . $queryString ?>" <?= $i == $pageActuelle ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($pageActuelle < $totalPages): ?>
            <a href="?page=logement-liste&pageNum=<?= ($pageActuelle + 1) . $queryString ?>" class="next">Suivant</a>
        <?php endif; ?>
    </div>

</main>

<?php include __DIR__ . '/../footer.php'; ?>

<script>
  document.querySelectorAll('.container-logements img').forEach(img => {
    img.onerror = function() {
      this.style.display = 'none';
    }
  });
</script>

<script>
async function toggleFavori(element) {
    const iconDiv = element.closest('.favori-icon');
    const svgElement = iconDiv.querySelector('svg');
    const logementId = iconDiv.getAttribute('data-id');
    const isFavori = svgElement.classList.contains('active');

    try {
        const response = await fetch('/Web-Mimba/index.php?page=toggle-favori', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                logement_id: logementId,
                action: isFavori ? 'remove' : 'add'
            })
        });

        if (response.ok) {
            // Animation
            iconDiv.style.transform = 'scale(1.2)';
            setTimeout(() => {
                iconDiv.style.transform = 'scale(1)';
            }, 200);
            
            // Changement d'image
            svgElement.classList.toggle('active');
        } else {
            const data = await response.json();
            console.error(data.error || 'Erreur inconnue');
        }
    } catch (error) {
        console.error('Erreur:', error);
    }
}
</script>

</body>
</html>
