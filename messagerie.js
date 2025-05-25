document.addEventListener('DOMContentLoaded', function() {
  const messagesContainer = document.getElementById('messages');
  const messageInput = document.getElementById('message-text');
  const sendButton = document.getElementById('send-button');
  
  // Détermine le type de conversation
  const urlParams = new URLSearchParams(window.location.search);
  const type = urlParams.get('type');
  const recipient = urlParams.get('recipient');
  
  // Charger les messages existants
  loadMessages();
  
  // Envoyer un message
  sendButton.addEventListener('click', sendMessage);
  messageInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });
  
  // Rafraîchir les messages toutes les 5 secondes
  setInterval(loadMessages, 5000);
  
  function loadMessages() {
    let url = '/Controllers/getMessages.php';
    
    if (type === 'user' && recipient) {
      url += `?recipient=${encodeURIComponent(recipient)}`;
    }
    
    fetch(url)
      .then(response => response.json())
      .then(messages => {
        messagesContainer.innerHTML = '';
        messages.forEach(msg => {
          const messageDiv = document.createElement('div');
          messageDiv.className = msg.is_admin ? 'admin-message message' : 'user-message message';
          messageDiv.innerHTML = `
            <strong>${msg.sender}:</strong>
            <p>${msg.message}</p>
            <small>${new Date(msg.sent_at).toLocaleString()}</small>
          `;
          messagesContainer.appendChild(messageDiv);
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      });
  }
  
  function sendMessage() {
    const message = messageInput.value.trim();
    if (message) {
      let formData = new FormData();
      formData.append('message', message);
      
      if (type === 'user' && recipient) {
        formData.append('recipient', recipient);
      }
      
      fetch('/Controllers/sendMessage.php', {
        method: 'POST',
        body: formData
      })
      .then(() => {
        messageInput.value = '';
        loadMessages();
      });
    }
  }
});