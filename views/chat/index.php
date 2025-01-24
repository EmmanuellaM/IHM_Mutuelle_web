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
    --primary-color: #2563eb;
    --secondary-color: #f3f4f6;
    --text-primary: #1f2937;
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --success-color: #10b981;
    --white: #ffffff;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.mutuelle-chat {
    display: flex;
    height: calc(100vh - 180px);
    background-color: var(--white);
    border-radius: 12px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin: 80px 20px 20px 20px;
}

/* Sidebar styles */
.mutuelle-chat-sidebar {
    width: 300px;
    background-color: var(--white);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
}

.mutuelle-chat-user-profile {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.mutuelle-chat-user-profile img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.mutuelle-chat-user-info h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 16px;
}

.mutuelle-chat-user-info p {
    margin: 0;
    color: var(--success-color);
    font-size: 12px;
}

.mutuelle-chat-search {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
}

.mutuelle-chat-search input {
    width: 100%;
    padding: 8px 16px;
    border: 1px solid var(--border-color);
    border-radius: 20px;
    outline: none;
    font-size: 14px;
}

.mutuelle-chat-users-list {
    flex: 1;
    overflow-y: auto;
}

.mutuelle-chat-user-item {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.mutuelle-chat-user-item:hover,
.mutuelle-chat-user-item.active {
    background-color: var(--secondary-color);
}

.mutuelle-chat-user-item img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.mutuelle-chat-user-item .user-info {
    flex: 1;
}

.mutuelle-chat-user-item h4 {
    margin: 0;
    font-size: 14px;
    color: var(--text-primary);
}

.mutuelle-chat-user-item p {
    margin: 4px 0 0;
    font-size: 12px;
    color: var(--text-secondary);
}

/* Main chat area */
.mutuelle-chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: var(--white);
}

.mutuelle-chat-header {
    padding: 16px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.mutuelle-chat-header img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.mutuelle-chat-header h3 {
    margin: 0;
    font-size: 16px;
    color: var(--text-primary);
}

.mutuelle-chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
    background-color: var(--secondary-color);
}

.mutuelle-chat-message {
    max-width: 70%;
    display: flex;
    flex-direction: column;
}

.mutuelle-chat-message.sent {
    align-self: flex-end;
}

.mutuelle-chat-message .message-content {
    padding: 12px 16px;
    border-radius: 16px;
    background-color: var(--white);
    color: var(--text-primary);
    box-shadow: var(--shadow);
}

.mutuelle-chat-message.sent .message-content {
    background-color: var(--primary-color);
    color: var(--white);
}

.mutuelle-chat-message .message-time {
    font-size: 12px;
    color: var(--text-secondary);
    align-self: flex-end;
}

.mutuelle-chat-input {
    padding: 16px;
    border-top: 1px solid var(--border-color);
    background-color: var(--white);
}

.mutuelle-chat-input form {
    display: flex;
    gap: 12px;
}

.mutuelle-chat-input input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid var(--border-color);
    border-radius: 24px;
    outline: none;
    font-size: 14px;
}

.mutuelle-chat-input button {
    padding: 12px 24px;
    background-color: var(--primary-color);
    color: var(--white);
    border: none;
    border-radius: 24px;
    cursor: pointer;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.mutuelle-chat-input button:hover {
    background-color: #1d4ed8;
}

.mutuelle-chat-placeholder {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    color: var(--text-secondary);
    font-size: 16px;
    text-align: center;
    padding: 20px;
}

.mutuelle-chat-placeholder i {
    font-size: 48px;
    color: var(--primary-color);
}

.mutuelle-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    background-color: var(--secondary-color);
}

.chat-message {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.chat-message.sent {
    align-items: flex-end;
}

.chat-message.received {
    align-items: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 10px 15px;
    border-radius: 15px;
    background-color: var(--white);
    box-shadow: var(--shadow);
}

.chat-message.sent .message-content {
    background-color: var(--primary-color);
    color: var(--white);
}

.message-sender {
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 5px;
}

.message-text {
    margin-bottom: 5px;
}

.message-time {
    font-size: 11px;
    color: var(--text-secondary);
}

.chat-message.sent .message-time,
.chat-message.sent .message-sender {
    color: rgba(255, 255, 255, 0.8);
}

.mutuelle-chat-input {
    display: flex;
    padding: 20px;
    background-color: var(--white);
    border-top: 1px solid var(--border-color);
}

.mutuelle-chat-input input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: 25px;
    margin-right: 10px;
}

.mutuelle-chat-input input:disabled {
    background-color: var(--secondary-color);
    cursor: not-allowed;
}

.mutuelle-chat-input button {
    padding: 10px 20px;
    background-color: var(--primary-color);
    color: var(--white);
    border: none;
    border-radius: 25px;
    cursor: pointer;
}

.mutuelle-chat-input button:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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