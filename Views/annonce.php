<?php include __DIR__ . '/../header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Logement</title>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/logement.css">
    <link rel="stylesheet" href="/styles/annonce.css">
</head>
<body>

<main class="annonce-container">
    <?php if (!empty($logement)): ?>
        <div class="annonce-header">
            <h1 class="annonce-title"><?= htmlspecialchars($logement['Nom']) ?></h1>
            <div class="annonce-location">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                <?= htmlspecialchars($logement['Zone']) ?>, <?= htmlspecialchars($logement['regions']) ?>
            </div>
        </div>

        <div class="annonce-content">
            <div class="annonce-gallery">
                <?php if (!empty($logement['photo'])): ?>
                    <img src="<?= htmlspecialchars($logement['photo']) ?>" 
                         alt="Photo du logement" 
                         onerror="this.style.display='none'">
                <?php endif; ?>
            </div>

            <div class="annonce-details">
                <div class="detail-section">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>Description</h3>
                    <p><?= nl2br(htmlspecialchars($logement['Description'])) ?></p>
                </div>

                <div class="detail-section">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>Localisation</h3>
                    <div class="icon-text">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                        <span><?= htmlspecialchars($logement['Zone']) ?>, <?= htmlspecialchars($logement['regions']) ?></span>
                    </div>
                </div>

                <div class="detail-section">
                    <h3><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18 2.01L6 2c-1.1 0-2 .89-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.11-.9-1.99-2-1.99zM18 20H6v-9.02h12V20zm0-11H6V4h12v5z"/></svg>Services</h3>
                    <div class="services-list">
                        <?php
                        $servicesJson = $logement['house_services'] ?? '{}';
                        $servicesData = json_decode($servicesJson, true) ?? [];
                   
                        $pmrServices = $servicesData['house_service']['Accessible PMR'] ?? [];
                        
                        function displayService($serviceName) {
                            echo '<span class="service-tag">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>';
                            echo htmlspecialchars($serviceName);
                            echo '</span>';
                        }
                     
                        if (!empty($pmrServices)) {
                            foreach ($pmrServices as $service) {
                                if (trim($service)) {
                                    displayService($service);
                                }
                            }
                        }
                        
                        $otherServices = [
                            'Cuisine collective',
                            'Douche individuelle',
                            'Garage à vélos',
                            'Station réparation vélos',
                            'Internet / Wifi',
                            'Laverie',
                            'Salle de sport',
                            'Surveillance / Sécurité',
                            'Distribution protections périodiques',
                            'Distributeur boissons confiseries'
                        ];
                        
                        foreach ($otherServices as $service) {
                            if (isset($servicesData[$service]) && $servicesData[$service]) {
                                displayService($service);
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="annonce-prices">
                    <h3>Tarifs</h3>
                    <?php 
                    $prixBrut = $logement['prix'] ?? '';
                    $prixBrut = trim($prixBrut, '{} ');
                    $prixList = explode(',', $prixBrut);
                    
                    $prixList = array_filter($prixList, function($p) {
                        $val = floatval(trim($p));
                        return $val > 0;
                    });

                    $prixList = array_unique(array_map('trim', $prixList));
                    sort($prixList, SORT_NUMERIC);
                    $prixList = array_values($prixList);

                    if (!empty($prixList)): 
                        foreach ($prixList as $index => $prix): ?>
                            <div class="price-item">
                                <span class="price-label">Option <?= $index + 1 ?></span>
                                <span class="price-value"><?= htmlspecialchars($prix) ?> €</span>
                            </div>
                        <?php endforeach; 
                    else: ?>
                        <p>Aucun tarif disponible pour ce logement.</p>
                    <?php endif; ?>
                </div>

                <div class="annonce-actions">
                    <button class="btn-primary" onclick="location.href='Views/Message.php'">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        Contacter le propriétaire
                    </button>
                    <button class="btn-secondary" id="moreDetailsBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"/><path d="M11 7h2v2h-2zm0 4h2v6h-2z"/></svg>
                        Plus d'informations
                    </button>
                </div>

                <div class="more-details" id="moreDetails">
                    <h3>Informations complémentaires</h3>
                    <div class="detail-section">
                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span><strong>Adresse complète :</strong> <?= htmlspecialchars($logement['address']) ?></span>
                        </div>

                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <span><strong>Email :</strong> <?= htmlspecialchars($logement['mail'] ?? 'Non spécifié') ?></span>
                        </div>

                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56-.35-.12-.74-.03-1.01.24l-1.57 1.97c-2.83-1.35-5.48-3.9-6.89-6.83l1.95-1.66c.27-.28.35-.67.24-1.02-.37-1.11-.56-2.3-.56-3.53 0-.54-.45-.99-.99-.99H4.19C3.65 3 3 3.24 3 3.99 3 13.28 10.73 21 20.01 21c.71 0 .99-.63.99-1.18v-3.45c0-.54-.45-.99-.99-.99z"/></svg>
                            <span><strong>Téléphone :</strong> <?= htmlspecialchars($logement['phone'] ?? 'Non spécifié') ?></span>
                        </div>

                        <?php if (!empty($logement['Adresse'])): ?>
                        <div class="icon-text">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span><strong>Compléments donnés par le propriétaire :</strong> <?= htmlspecialchars(trim($logement['Adresse'], ' "\'')) ?></span>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <button class="back-link" onclick="window.history.back()">← Retour à la liste</button>
    <?php else: ?>
        <p class="error-message">Logement introuvable.</p>
    <?php endif; ?>
</main>

<script>
document.getElementById('moreDetailsBtn').addEventListener('click', function() {
    const detailsSection = document.getElementById('moreDetails');
    detailsSection.classList.toggle('active');
    this.textContent = detailsSection.classList.contains('active') ? 
        'Moins d\'informations' : 'Plus d\'informations';
});
</script>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>