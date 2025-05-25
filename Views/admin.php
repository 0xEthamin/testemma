<?php include __DIR__ . '/../header.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panneau Administrateur</title>
  <link rel="stylesheet" href="/Web-Mimba/styles/style.css" />
  <link rel="stylesheet" href="/Web-Mimba/styles/admin.css" />
</head>
<body>  
<section class="admin-panel">
  <h1>Panneau Administrateur</h1>

  <!-- Utilisateurs non vérifiés -->
  <h2>Liste des utilisateurs non vérifiés</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Date d'inscription</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usersNonVerified as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= $user['created_at'] ?></td>
          <td>
            <a href="validate_user.php?id=<?= $user['id'] ?>">Valider</a> |
            <a href="delete_user.php?id=<?= $user['id'] ?>">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Utilisateurs vérifiés -->
  <h2>Liste des utilisateurs vérifiés</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Date d'inscription</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usersVerified as $user): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= $user['created_at'] ?></td>
          <td>
            <a href="delete_user.php?id=<?= $user['id'] ?>">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h2>Biens en attente de validation</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nom</th><th>Description</th><th>Adresse</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($logementsNonValides as $logement): ?>
        <tr>
          <td><?= $logement['id'] ?></td>
          <td><?= htmlspecialchars($logement['Nom']) ?></td>
          <td><?= htmlspecialchars($logement['Description']) ?></td>
          <td><?= htmlspecialchars($logement['Adresse']) ?></td>
          <td>
            <a href="validate_bien.php?id=<?= $logement['id'] ?>">Valider</a> |
            <a href="delete_bien.php?id=<?= $logement['id'] ?>" onclick="return confirm('Supprimer ce bien ?');">Supprimer</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


  <!-- Messages contact -->
  <h2>Messages reçus depuis le formulaire de contact</h2>
  <table>
    <thead>
      <tr>
        <th>Nom</th><th>Email</th><th>Message</th><th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($messages as $msg): ?>
        <tr>
          <td><?= htmlspecialchars($msg['name']) ?></td>
          <td><?= htmlspecialchars($msg['email']) ?></td>
          <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
          <td><?= $msg['created_at'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Messagerie admin -->
<h2>Messagerie avec les utilisateurs</h2>
<div class="admin-messaging">
  <aside class="user-list">
    <h3>Utilisateurs en attente</h3>
    <ul id="user-list">
      <?php foreach ($userMessages as $user): ?>
        <li onclick="loadConversation(<?= $user['id'] ?>)" class="user-item" tabindex="0" role="button" aria-pressed="false">
          <span class="user-name"><?= htmlspecialchars($user['username']) ?></span>
          <span class="user-id">(ID: <?= $user['id'] ?>)</span>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>
  
  <div class="conversation">
    <h3>Conversation</h3>
    <label for="message-limit-select">Afficher les messages :</label>
    <select id="message-limit-select" onchange="changeMessageLimit()">
      <option value="5" selected>5 derniers</option>
      <option value="10">10 derniers</option>
      <option value="20">20 derniers</option>
      <option value="all">Tous</option>
    </select>

    <div id="conversation-messages" class="conversation-messages"></div>

    <form id="admin-message-form" class="message-input">
      <textarea id="admin-message-text"
                placeholder="Écrivez votre réponse ici..." name="message" required></textarea>
      <button type="submit" class="send-admin-btn">Envoyer</button>
    </form>
  </div>
</div>
  </section>
</div>


<script>
let currentUserId = null;

let currentLimit = 5; // valeur par défaut

document.getElementById('admin-message-form').addEventListener('submit', function(e) {
    e.preventDefault();  // bloque la soumission classique + rechargement
    sendAdminMessage();
});

function loadConversation(userId) {
    console.log("Chargement conversation pour user:", userId);
    currentUserId = userId;

    const container = document.getElementById('conversation-messages');
    container.innerHTML = '<p>Chargement...</p>';
    
    fetch(`/Web-Mimba/Controllers/getMessage.php?user=${userId}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            displayMessages(data, currentLimit);
        })
        .catch(err => {
            console.error("Erreur:", err);
            container.innerHTML = `<div class="error">Erreur: ${err.message}</div>`;
        });
}


function displayMessages(messages, limit = 5) {
    const container = document.getElementById('conversation-messages');
    container.innerHTML = '';

    if (!messages || messages.length === 0) {
        container.innerHTML = '<p>Aucun message à afficher</p>';
        return;
    }

    // Si limit = 'all', afficher tout, sinon limiter
    let messagesToShow;
    if (limit === 'all') {
        messagesToShow = messages;
    } else {
        messagesToShow = messages.slice(-limit);
    }

    messagesToShow.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = msg.is_admin ? 'admin-message' : 'user-message';
        
        messageDiv.innerHTML = `
            <div class="message-header">
                <strong>${msg.sender}</strong>
                <span class="message-time">
                    ${new Date(msg.sent_at).toLocaleString('fr-FR')}
                </span>
            </div>
            <div class="message-content">${msg.message}</div>
        `;
        
        container.appendChild(messageDiv);
    });

    container.scrollTop = container.scrollHeight;
}

function changeMessageLimit() {
    const select = document.getElementById('message-limit-select');
    const newLimit = select.value === 'all' ? 'all' : parseInt(select.value, 10);

    currentLimit = newLimit;
    if (currentUserId) {
        loadConversation(currentUserId);
    }
}


async function sendAdminMessage() {
    const messageInput = document.getElementById('admin-message-text');
    const message = messageInput.value.trim();

    if (!currentUserId || !message) {
        alert("Veuillez sélectionner un utilisateur et saisir un message");
        return;
    }

    try {
        const response = await fetch('/Web-Mimba/Controllers/sendAdminMessage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `user_id=${encodeURIComponent(currentUserId)}&message=${encodeURIComponent(message)}`
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const text = await response.text();

        // Essayer de parser le JSON
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            throw new Error("Réponse invalide du serveur: " + text);
        }

        if (data.status === 'success') {
            messageInput.value = '';
            loadConversation(currentUserId);
        } else {
            throw new Error(data.error || "Erreur lors de l'envoi du message");
        }
    } catch (error) {
        console.error("Erreur d'envoi:", error);
        alert("Erreur: " + error.message);
    }
}


// Rafraîchissement automatique
setInterval(() => {
    if (currentUserId) loadConversation(currentUserId);
}, 5000);
</script>


<?php include __DIR__ . '/../footer.php'; ?>
</body>
</html>
