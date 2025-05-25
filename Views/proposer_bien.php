<?php
include __DIR__ . '/../header.php';

// Connexion à la base de données
$host = 'localhost';
$dbname = 'web_mimba';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $adresse = trim($_POST['adresse'] ?? '');
    $zone = trim($_POST['zone'] ?? '');
    $region = trim($_POST['region'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');



    // Gestion de l'image
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);
        if (in_array($fileType, $allowedTypes)) {
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_') . '.' . $extension;
            $targetPath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/' . $fileName;
            } else {
                $error = "<span style='color:#c0392b;font-weight:bold;'>Erreur lors de l'envoi de l'image.</span>";
            }
        } else {
            $error = "Format d'image non supporté. Formats acceptés : JPEG, PNG, GIF, WEBP.";
        }
    }

    // Validation des champs
    if (!$error) {
        if ($titre && $description && $prix > 0 && $adresse) {
            try {
                // Insertion dans la table logements avec est_valide = 0
                // Générer un ID unique
                $id = null;
                try {
                    $stmt = $pdo->query("SELECT MAX(id) AS max_id FROM logements");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $id = ($result['max_id'] ?? 0) + 1;
                } catch (Exception $e) {
                    $error = "Erreur lors de la génération de l'ID : " . $e->getMessage();
                }
                $sql = "INSERT INTO logements (id, Nom, Description, Adresse, photo, Zone, regions, mail, phone, est_valide)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    $id,
                    $titre,
                    $description,
                    $adresse,
                    $imagePath,
                    $zone,
                    $region,
                    $email,
                    $phone
                ]); 


                // Redirection vers admin.php
                $success = "Votre bien a été proposé avec succès. Il sera visible après validation par un administrateur.";

            } catch (Exception $e) {
                $error = "Erreur lors de l'ajout du logement : " . $e->getMessage();
            }
        } else {
            $error = "Veuillez remplir tous les champs correctement.";
        }
    }
}
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

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="error"><?= $error ?></div>
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
