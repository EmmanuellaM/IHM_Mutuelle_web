<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Chat - ENSPY Mutuelle';

// Récupérer la liste des utilisateurs
$users = \app\models\User::find()
    ->where(['!=', 'id', Yii::$app->user->id])
    ->all();

// Convertir les utilisateurs en format JSON pour JavaScript
$usersJson = Json::encode(array_map(function($user) {
    $member = \app\models\Member::findOne(['user_id' => $user->id]);
    $administrator = \app\models\Administrator::findOne(['user_id' => $user->id]);
    
    $name = '';
    $avatarUrl = $user->avatar ? Yii::getAlias('@web/img/upload/') . $user->avatar : Yii::getAlias('@web/img/members.png');
    
    if ($user->type === 'ADMINISTRATOR' && $administrator) {
        $name = $administrator->username;
    } elseif ($user->type === 'MEMBER' && $member) {
        $name = $member->username;
    }
    
    // Ne retourner l'utilisateur que si on a trouvé son nom
    if (empty($name)) {
        return null;
    }
    
    return [
        'id' => $user->id,
        'username' => $name,
        'avatarUrl' => $avatarUrl,
        'isOnline' => true, // À implémenter avec un vrai statut en ligne
    ];
}, $users));

// Filtrer les utilisateurs null
$usersJson = Json::encode(array_values(array_filter(Json::decode($usersJson))));

?>

<style>
:root {
    --primary-color: #3498db;
    --primary-dark: #2980b9;
    --secondary-color: #f8fafc;
    --text-primary: #2c3e50;
    --text-secondary: #64748b;
    --border-color: #e2e8f0;
    --success-color: #10b981;
    --white: #ffffff;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

.mutuelle-chat {
    display: flex;
    height: calc(100vh - 180px);
    background-color: var(--white);
    border-radius: 20px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin: 80px 20px 20px 20px;
    transition: var(--transition);
}

/* Sidebar styles */
.mutuelle-chat-sidebar {
    width: 320px;
    background-color: var(--white);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    transition: var(--transition);
}

.mutuelle-chat-user-profile {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(to right, var(--primary-color), var(--primary-dark));
    color: var(--white);
}

.mutuelle-chat-user-profile img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--white);
    transition: var(--transition);
}

.mutuelle-chat-user-profile img:hover {
    transform: scale(1.1);
}

.mutuelle-chat-user-info h3 {
    margin: 0;
    color: var(--white);
    font-size: 18px;
    font-weight: 600;
}

.mutuelle-chat-user-info p {
    margin: 4px 0 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
}

.mutuelle-chat-search {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    background-color: var(--secondary-color);
}

.mutuelle-chat-search input {
    width: 100%;
    padding: 12px 20px;
    border: 2px solid var(--border-color);
    border-radius: 25px;
    outline: none;
    font-size: 14px;
    transition: var(--transition);
    background-color: var(--white);
}

.mutuelle-chat-search input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

.mutuelle-chat-users-list {
    flex: 1;
    overflow-y: auto;
    padding: 10px 0;
}

.mutuelle-chat-users-list::-webkit-scrollbar {
    width: 6px;
}

.mutuelle-chat-users-list::-webkit-scrollbar-track {
    background: var(--secondary-color);
}

.mutuelle-chat-users-list::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 3px;
}

.mutuelle-chat-user-item {
    padding: 15px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    cursor: pointer;
    transition: var(--transition);
    border-left: 3px solid transparent;
}

.mutuelle-chat-user-item:hover,
.mutuelle-chat-user-item.active {
    background-color: var(--secondary-color);
    border-left: 3px solid var(--primary-color);
}

.mutuelle-chat-user-item img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border-color);
    transition: var(--transition);
}

.mutuelle-chat-user-item:hover img {
    border-color: var(--primary-color);
}

.mutuelle-chat-user-item .user-info {
    flex: 1;
}

.mutuelle-chat-user-item h4 {
    margin: 0;
    font-size: 15px;
    color: var(--text-primary);
    font-weight: 600;
}

.mutuelle-chat-user-item p {
    margin: 4px 0 0;
    font-size: 13px;
    color: var(--text-secondary);
}

/* Main chat area */
.mutuelle-chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: var(--secondary-color);
}

.mutuelle-chat-header {
    padding: 20px;
    background-color: var(--white);
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 15px;
}

.mutuelle-chat-header img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--primary-color);
}

.mutuelle-chat-header h3 {
    margin: 0;
    font-size: 18px;
    color: var(--text-primary);
    font-weight: 600;
}

.mutuelle-chat-messages {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
    background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
    position: relative;
}

.mutuelle-chat-messages::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to bottom, rgba(246, 249, 252, 0.9) 0%, rgba(246, 249, 252, 0) 100%);
    pointer-events: none;
    z-index: 1;
}

.mutuelle-chat-messages::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100px;
    background: linear-gradient(to top, rgba(241, 245, 249, 0.9) 0%, rgba(241, 245, 249, 0) 100%);
    pointer-events: none;
    z-index: 1;
}

.mutuelle-chat-message {
    max-width: 75%;
    display: flex;
    flex-direction: column;
    gap: 5px;
    animation: messageAppear 0.3s ease;
    position: relative;
    z-index: 2;
}

.message-content {
    padding: 15px 20px;
    border-radius: 18px;
    font-size: 15px;
    line-height: 1.6;
    position: relative;
    transition: var(--transition);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.message-sent .message-content {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    border-bottom-right-radius: 5px;
    margin-right: 15px;
}

.message-received .message-content {
    background-color: var(--white);
    color: var(--text-primary);
    border-bottom-left-radius: 5px;
    margin-left: 15px;
}

.message-sent .message-content::before,
.message-received .message-content::before {
    content: '';
    position: absolute;
    bottom: 0;
    width: 12px;
    height: 12px;
}

.message-sent .message-content::before {
    right: -6px;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-dark) 50%, transparent 50%, transparent 100%);
    transform: rotate(-45deg);
}

.message-received .message-content::before {
    left: -6px;
    background: var(--white);
    transform: rotate(45deg);
}

.mutuelle-chat-input {
    padding: 25px 30px;
    background-color: var(--white);
    border-top: 1px solid var(--border-color);
    position: relative;
}

.mutuelle-chat-input::before {
    content: '';
    position: absolute;
    top: -10px;
    left: 0;
    right: 0;
    height: 10px;
    background: linear-gradient(to top, var(--white) 0%, rgba(255, 255, 255, 0) 100%);
}

.mutuelle-chat-input form {
    display: flex;
    gap: 20px;
    align-items: center;
    background: var(--secondary-color);
    border-radius: 30px;
    padding: 8px;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
}

.mutuelle-chat-input input {
    flex: 1;
    min-height: 50px;
    padding: 12px 25px;
    border: none;
    background: transparent;
    font-size: 15px;
    color: var(--text-primary);
    transition: var(--transition);
}

.mutuelle-chat-input input:focus {
    outline: none;
}

.mutuelle-chat-input input::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.mutuelle-chat-input button {
    padding: 15px 35px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    border: none;
    border-radius: 25px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 10px;
}

.mutuelle-chat-input button i {
    font-size: 18px;
}

.mutuelle-chat-input button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
}

.mutuelle-chat-input button:active {
    transform: translateY(0);
}

@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-sent {
    align-self: flex-end;
}

.message-received {
    align-self: flex-start;
}

.message-content {
    padding: 12px 18px;
    border-radius: 15px;
    font-size: 14px;
    line-height: 1.5;
    position: relative;
    transition: var(--transition);
}

.message-sent .message-content {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: var(--white);
    border-bottom-right-radius: 5px;
}

.message-received .message-content {
    background-color: var(--white);
    color: var(--text-primary);
    border-bottom-left-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.message-time {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
}
</style>

<div class="mutuelle-chat">
    <!-- Sidebar -->
    <div class="mutuelle-chat-sidebar">
        <!-- User Profile -->
        <div class="mutuelle-chat-user-profile">
            <img src="<?= Yii::$app->user->identity->avatar ? Yii::getAlias('@web/img/upload/') . Yii::$app->user->identity->avatar : Yii::getAlias('@web/img/members.png') ?>" alt="Profile">
            <div class="mutuelle-chat-user-info">
                <?php
                $currentUserName = '';
                if (Yii::$app->user->identity->type === 'ADMINISTRATOR') {
                    $administrator = \app\models\Administrator::findOne(['user_id' => Yii::$app->user->id]);
                    $currentUserName = $administrator ? $administrator->username : 'Administrateur';
                } else {
                    $member = \app\models\Member::findOne(['user_id' => Yii::$app->user->id]);
                    $currentUserName = $member ? $member->username : 'Membre';
                }
                ?>
                <h3><?= $currentUserName ?></h3>
                <p>En ligne</p>
            </div>
        </div>

        <!-- Search -->
        <div class="mutuelle-chat-search">
            <input type="text" placeholder="Rechercher un utilisateur...">
        </div>

        <!-- Users List -->
        <div class="mutuelle-chat-users-list">
            <?php foreach ($users as $user): ?>
                <?php
                $member = \app\models\Member::findOne(['user_id' => $user->id]);
                $administrator = \app\models\Administrator::findOne(['user_id' => $user->id]);
                
                $userName = '';
                $avatarUrl = $user->avatar ? Yii::getAlias('@web/img/upload/') . $user->avatar : Yii::getAlias('@web/img/members.png');
                
                if ($user->type === 'ADMINISTRATOR' && $administrator) {
                    $userName = $administrator->username;
                } elseif ($user->type === 'MEMBER' && $member) {
                    $userName = $member->username;
                }
                
                if (empty($userName)) {
                    continue;
                }
                ?>
                <div class="mutuelle-chat-user-item" data-user-id="<?= $user->id ?>">
                    <img src="<?= $avatarUrl ?>" alt="<?= $userName ?>">
                    <div class="user-info">
                        <h4><?= $userName ?></h4>
                        <p>Cliquez pour discuter</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="mutuelle-chat-main">
        <!-- Chat Header -->
        <div class="mutuelle-chat-header">
            <div class="selected-user-info">
                <img src="" alt="" id="selected-user-avatar" style="display: none;">
                <h3 id="selected-user-name">Sélectionnez un utilisateur</h3>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="mutuelle-chat-messages">
            <!-- Les messages seront ajoutés ici dynamiquement -->
        </div>

        <!-- Input Area -->
        <div class="mutuelle-chat-input">
            <input type="text" placeholder="Tapez votre message..." disabled>
            <button type="button" disabled>Envoyer</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = <?= $currentUserId ?>;
    const chatMessages = document.querySelector('.mutuelle-chat-messages');
    const messageInput = document.querySelector('.mutuelle-chat-input input');
    const sendButton = document.querySelector('.mutuelle-chat-input button');
    const selectedUserName = document.getElementById('selected-user-name');
    const selectedUserAvatar = document.getElementById('selected-user-avatar');
    let selectedUserId = null;
    let messageUpdateInterval = null;

    // Fonction pour charger les messages
    function loadMessages(userId) {
        fetch(`/chat/get-messages?receiver_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    chatMessages.innerHTML = '';
                    data.messages.forEach(message => {
                        const isCurrentUser = message.sender_id === currentUserId;
                        const messageElement = document.createElement('div');
                        messageElement.className = `chat-message ${isCurrentUser ? 'sent' : 'received'}`;
                        messageElement.innerHTML = `
                            <div class="message-content">
                                <div class="message-sender">${message.sender_name}</div>
                                <div class="message-text">${message.message}</div>
                                <div class="message-time">${new Date(message.created_at * 1000).toLocaleTimeString()}</div>
                            </div>
                        `;
                        chatMessages.appendChild(messageElement);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(error => console.error('Erreur:', error));
    }

    // Fonction pour envoyer un message
    function sendMessage() {
        if (!selectedUserId || !messageInput.value.trim()) return;

        const message = messageInput.value.trim();
        fetch('/chat/send-message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            body: JSON.stringify({
                message: message,
                receiver_id: selectedUserId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                loadMessages(selectedUserId);
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    // Gestionnaire d'événements pour le clic sur un utilisateur
    document.querySelectorAll('.mutuelle-chat-user-item').forEach(userItem => {
        userItem.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.querySelector('.user-info h4').textContent;
            const userAvatar = this.querySelector('img').src;
            
            document.querySelectorAll('.mutuelle-chat-user-item').forEach(item => 
                item.classList.remove('active')
            );
            this.classList.add('active');
            
            selectedUserId = userId;
            selectedUserName.textContent = userName;
            const avatarImg = document.getElementById('selected-user-avatar');
            avatarImg.src = userAvatar;
            avatarImg.style.display = 'block';
            
            // Activer les contrôles de chat
            messageInput.disabled = false;
            sendButton.disabled = false;
            
            // Arrêter l'intervalle précédent s'il existe
            if (messageUpdateInterval) {
                clearInterval(messageUpdateInterval);
            }
            
            // Charger les messages initiaux
            loadMessages(userId);
            
            // Mettre en place un nouvel intervalle pour cette conversation
            messageUpdateInterval = setInterval(() => loadMessages(userId), 5000);
        });
    });

    // Gestionnaire d'événements pour l'envoi de message
    sendButton.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});
</script>