<?php include __DIR__ . '/../header.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Messagerie interne</title>
  <link rel="stylesheet" href="/styles/style.css">
  <link rel="stylesheet" href="/styles/messagerie.css">
</head>
<body>
<div class="messagerie-container">
    <h1>Messagerie</h1>
    
    <div class="chat-container">
        <div class="messages" id="messages">
        </div>
        
        <div class="message-input">
            <textarea id="message-text" placeholder="Écrivez votre message..."></textarea>
            <button id="send-button">Envoyer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages');
    const messageInput = document.getElementById('message-text');
    const sendButton = document.getElementById('send-button');
    const userId = <?= $_SESSION['user']['id'] ?? 0 ?>;

    function displayMessages(messages) {
        messagesContainer.innerHTML = '';
        if (!messages || messages.length === 0) {
            messagesContainer.innerHTML = '<p>Aucun message</p>';
            return;
        }

        messages.forEach(msg => {
            const msgDiv = document.createElement('div');
            msgDiv.className = msg.is_admin ? 'admin-message' : 'user-message';
            msgDiv.innerHTML = `
                <div><strong>${msg.sender}</strong> <small>${new Date(msg.sent_at).toLocaleString()}</small></div>
                <div>${msg.message}</div>
            `;
            messagesContainer.appendChild(msgDiv);
        });

        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    async function loadMessages() {
        try {
            const response = await fetch('/Controllers/getUserMessages.php');
            const data = await response.json();
            if (data.error) throw new Error(data.error);
            displayMessages(data);
        } catch (err) {
            console.error("Erreur de chargement:", err);
        }
    }

    async function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('message', message);
        formData.append('user_id', userId);

        try {
            const response = await fetch('/Controllers/sendMessage.php', {
                method: 'POST',
                body: formData
            });
            
            if (response.ok) {
                messageInput.value = '';
                await loadMessages();
            }
        } catch (error) {
            console.error("Erreur d'envoi:", error);
        }
    }

    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    loadMessages();
    setInterval(loadMessages, 5000);
});
</script>


<?php include __DIR__ . '/../footer.php'; ?>
</body>