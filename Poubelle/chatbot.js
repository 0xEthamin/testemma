// chatbot.js - Version adaptée pour accueil.php
console.log("Chatbot.js chargé avec succès");

// Fonction pour ajouter un message dans le chat
function addMessage(sender, message) {
    const chatMessages = document.getElementById('chat-messages');
    if (!chatMessages) {
        console.error("Élément chat-messages introuvable");
        return;
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = sender === 'user' ? 'user-message' : 'bot-message';
    
    const messageP = document.createElement('p');
    messageP.textContent = message;
    
    messageDiv.appendChild(messageP);
    chatMessages.appendChild(messageDiv);
    
    // Faire défiler vers le bas
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Fonction pour gérer les actions du chatbot
function handleChatAction(action) {
    const chatOptions = document.getElementById('chat-options');
    const citySelection = document.getElementById('city-selection');

    switch(action) {
        case 'search':
            addMessage('bot', 'Quelle ville cherchez-vous ?');
            if (chatOptions) chatOptions.style.display = 'none';
            if (citySelection) citySelection.style.display = 'block';
            break;
            
        case 'admin':
            addMessage('bot', 'Un administrateur vous contactera bientôt. Merci de patienter.');
            break;
            
        case 'faq':
            addMessage('bot', 'Etulogis est une plateforme de recherche de logements étudiants. Utilisez la barre de recherche principale ou discutez avec moi pour trouver votre logement idéal !');
            break;
            
        case 'cgu':
            addMessage('bot', 'Vous pouvez consulter nos conditions générales d\'utilisation sur la page dédiée.');
            break;
            
        default:
            addMessage('bot', 'Je ne comprends pas cette demande. Pouvez-vous reformuler ?');
    }
}

// Initialisation du chatbot
function initializeChatbot() {
    console.log("Initialisation du chatbot...");

    // Vérification des éléments essentiels
    if (!document.getElementById('chatbot')) {
        console.error("Élément chatbot introuvable");
        return;
    }

    // Gestion des boutons du chat
    const chatButtons = document.querySelectorAll('.chat-btn');
    chatButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            console.log("Bouton cliqué:", action);
            handleChatAction(action);
        });
    });

    // Gestion de la sélection de ville
    const confirmBtn = document.querySelector('.confirm-btn');
    const cancelBtn = document.querySelector('.cancel-btn');
    const citySelect = document.getElementById('city-select');

    if (confirmBtn && cancelBtn && citySelect) {
        confirmBtn.addEventListener('click', function() {
            const selectedCity = citySelect.value;
            addMessage('user', selectedCity);
            addMessage('bot', `Recherche de logements à ${selectedCity} en cours...`);
            
            // Masquer la sélection de ville et réafficher les options
            document.getElementById('city-selection').style.display = 'none';
            document.getElementById('chat-options').style.display = 'block';
            
            // Ici vous pourriez lancer une recherche réelle
            // window.location.href = `/recherche?ville=${selectedCity}`;
        });

        cancelBtn.addEventListener('click', function() {
            document.getElementById('city-selection').style.display = 'none';
            document.getElementById('chat-options').style.display = 'block';
            addMessage('bot', 'Comment puis-je vous aider ?');
        });
    }

    console.log("Chatbot initialisé avec succès");
}

// Fonction pour afficher/masquer le chat
window.toggleChat = function() {
    const chatbot = document.getElementById('chatbot');
    if (chatbot) {
        const currentDisplay = chatbot.style.display;
        chatbot.style.display = (currentDisplay === 'none' || currentDisplay === '') ? 'block' : 'none';
        console.log("Chatbot togglé. État:", chatbot.style.display);
    }
};

// Initialisation lorsque le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeChatbot);
} else {
    initializeChatbot();
}