<?php
/**
 * Page de connexion admin directe - bypass du routing Yii2
 * URL: https://ihm-mutuelle-web.onrender.com/direct-admin-login.php
 */

// Charger Yii
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

// Traiter la connexion si POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // Chercher l'utilisateur
        $user = \app\models\User::findOne(['login' => $username]);
        
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
            exit;
        }
        
        // Vérifier le mot de passe
        if (!$user->validatePassword($password)) {
            echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
            exit;
        }
        
        // Vérifier qu'il est admin
        $admin = \app\models\Administrator::findOne(['user_id' => $user->id]);
        if (!$admin) {
            echo json_encode(['success' => false, 'message' => 'Utilisateur non administrateur']);
            exit;
        }
        
        // Connecter l'utilisateur
        Yii::$app->user->login($user);
        
        echo json_encode([
            'success' => true, 
            'redirect' => '/administrator/accueil',
            'message' => 'Connexion réussie !'
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
    exit;
}

// Afficher le formulaire
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin Directe</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 { color: #333; margin-bottom: 30px; text-align: center; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; font-weight: 500; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus { outline: none; border-color: #667eea; }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        }
        .error { background: #fee; color: #c33; border: 1px solid #fcc; }
        .success { background: #efe; color: #3c3; border: 1px solid #cfc; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔐 Connexion Admin Directe</h1>
        <div id="message" class="message"></div>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" value="admin" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" value="admin123" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p style="margin-top: 20px; text-align: center; color: #666; font-size: 14px;">
            Cette page bypass le routing Yii2 pour tester la connexion directement
        </p>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageEl = document.getElementById('message');
            
            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    messageEl.className = 'message success';
                    messageEl.textContent = '✅ ' + data.message;
                    messageEl.style.display = 'block';
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    messageEl.className = 'message error';
                    messageEl.textContent = '❌ ' + data.message;
                    messageEl.style.display = 'block';
                }
            })
            .catch(error => {
                messageEl.className = 'message error';
                messageEl.textContent = '❌ Erreur: ' + error.message;
                messageEl.style.display = 'block';
            });
        });
    </script>
</body>
</html>
