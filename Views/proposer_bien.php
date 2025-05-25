<?php
require_once(__DIR__ . '/../config.php');
include(__DIR__ . '/../header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proposer un Bien</title>
    <link rel="stylesheet" href="/styles/style.css" />
    <link rel="stylesheet" href="/styles/proposer_bien.css" />
</head>
<body>
<main>
    <h1>Proposer un Bien</h1>

    <?php if (!empty($success)): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="proposer-bien-form" method="post" action="" enctype="multipart/form-data">
        <label for="titre">Nom du bien</label>
        <input type="text" id="titre" name="titre" required maxlength="100" value="<?= htmlspecialchars($_POST['titre'] ?? '') ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description" required maxlength="1000"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

        <label for="zone">Zone</label>
        <select id="zone" name="zone" required>
            <option value="">-- Choisir une zone --</option>
            <?php
            $zonesStmt = $pdo->query("SELECT DISTINCT Zone FROM logements WHERE Zone IS NOT NULL AND Zone != '' ORDER BY Zone");
            while ($row = $zonesStmt->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($_POST['zone'] ?? '') === $row['Zone'] ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($row['Zone']) . "\" $selected>" . htmlspecialchars($row['Zone']) . "</option>";
            }
            ?>
        </select>

        <label for="adresse">Adresse</label>
        <input type="text" id="adresse" name="adresse" required maxlength="255" value="<?= htmlspecialchars($_POST['adresse'] ?? '') ?>">

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required maxlength="100" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="phone">Numéro de téléphone</label>
        <input type="tel" id="phone" name="phone" required maxlength="20" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

        <label for="image">Photo du bien (facultative)</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">

        <label for="region">Région</label>
        <select id="region" name="region" required>
            <option value="">-- Choisir une région --</option>
            <?php
            $regionsStmt = $pdo->query("SELECT DISTINCT regions FROM logements WHERE regions IS NOT NULL AND regions != '' ORDER BY regions");
            while ($row = $regionsStmt->fetch(PDO::FETCH_ASSOC)) {
                $selected = ($_POST['region'] ?? '') === $row['regions'] ? 'selected' : '';
                echo "<option value=\"" . htmlspecialchars($row['regions']) . "\" $selected>" . htmlspecialchars($row['regions']) . "</option>";
            }
            ?>
        </select>

        <label for="prix">Prix (€)</label>
        <input type="number" id="prix" name="prix" required min="1" step="0.01" value="<?= htmlspecialchars($_POST['prix'] ?? '') ?>">

        <button type="submit">Proposer</button>
    </form>
</main>

<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
