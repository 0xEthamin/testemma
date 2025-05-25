
function lancerRecherche() {
  const ville = document.getElementById('ville').value.trim();
  const budget = document.getElementById('budget').value.trim();
  const zone = encodeURIComponent(ville);

  if (!ville) {
    alert('Veuillez saisir une ville.');
    return;
  }

  let url = `index.php?page=logement-liste&zone=${zone}`;
  if (budget) {
    url += `&budget=${encodeURIComponent(budget)}`;
  }

  window.location.href = url;
}

function addMessage(sender, text) {
  const chatMessages = document.getElementById('chat-messages');
  if (!chatMessages) {
    console.error("Élément chat-messages introuvable");
    return;
  }

  const messageDiv = document.createElement('div');
  messageDiv.className = sender === 'user' ? 'user-message' : 'bot-message';

  const messageP = document.createElement('p');
  messageP.textContent = text;

  messageDiv.appendChild(messageP);
  chatMessages.appendChild(messageDiv);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function handleChatAction(action) {
  const chatOptions = document.getElementById('chat-options');
  const citySelection = document.getElementById('city-selection');

  if (!chatOptions || !citySelection) {
    console.error("Éléments chat-options ou city-selection introuvables");
    return;
  }

  switch (action) {
    case 'search':
      addMessage('bot', 'Quelle ville cherchez-vous ?');
      chatOptions.style.display = 'none';
      citySelection.style.display = 'flex';
      break;

    case 'admin':
      addMessage('user', 'Je souhaite parler avec un administrateur');
      addMessage('bot', 'Connexion à l\'administrateur...');
      setTimeout(() => {
        window.location.href = "/Views/messagerie.php";
      }, 1500);
      break;

    case 'faq':
      addMessage('user', 'Comment ça marche ?');
      addMessage('bot', 'Etulogis est une plateforme de recherche de logements étudiants. Utilisez la barre de recherche ou discutez avec moi pour trouver votre logement idéal !');
      break;

    case 'cgu':
      addMessage('user', 'Conditions d\'utilisation');
      addMessage('bot', 'Vous pouvez consulter nos conditions générales d\'utilisation sur la page dédiée.');
      break;

    case 'confirm-city':
      const selectedCity = document.getElementById('city-select').value;
      if (!selectedCity) {
        addMessage('bot', 'Veuillez sélectionner une ville.');
        return;
      }
      addMessage('user', `Je cherche à ${selectedCity}`);
      addMessage('bot', `Recherche de logements à ${selectedCity} en cours...`);
      setTimeout(() => {
        window.location.href = `/index.php?page=logement-liste&zone=${encodeURIComponent(selectedCity)}`;
      }, 1000);
      break;

    case 'cancel-city':
      citySelection.style.display = 'none';
      chatOptions.style.display = 'flex';
      break;

    default:
      addMessage('bot', 'Je ne comprends pas cette demande. Pouvez-vous reformuler ?');
  }
}

window.toggleChat = function() {
  const chatbot = document.getElementById('chatbot');
  const toggleBtn = document.querySelector('.chat-toggle');
  
  if (chatbot.classList.contains('visible')) {
    chatbot.classList.remove('visible');
    toggleBtn.style.display = 'block';
  } else {
    chatbot.classList.add('visible');
    toggleBtn.style.display = 'none';
    document.getElementById('chat-options').style.display = 'flex';
    document.getElementById('city-selection').style.display = 'none';
  }
};

function initializeChatbot() {
  console.log("Initialisation du chatbot...");

  const buttons = document.querySelectorAll('.chat-btn');
  console.log("Nombre de boutons trouvés:", buttons.length);

  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.chat-btn');
    if (btn) {
      e.preventDefault();
      const action = btn.dataset.action;
      console.log("Action déclenchée:", action);
      handleChatAction(action);
    }
  });

  document.getElementById('chat-options').style.display = 'flex';
  document.getElementById('city-selection').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  console.log("DOM complètement chargé");
  
  initializeChatbot();

  const chatbot = document.getElementById('chatbot');
  if (chatbot) {
    chatbot.classList.remove('visible');
  }

  const toggleBtn = document.querySelector('.chat-toggle');
  if (toggleBtn) {
    toggleBtn.style.display = 'block';
  }
});