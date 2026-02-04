<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
$app = new yii\web\Application($config);

use app\models\Administrator;
use app\models\User;
use app\models\forms\AdministratorConnectionForm;

echo "=== Test de connexion administrateur ===\n\n";

// Simuler les données du formulaire
$administratorModel = new AdministratorConnectionForm();
$administratorModel->username = 'root';
$administratorModel->password = 'root';
$administratorModel->remember = false;

echo "1. Validation du formulaire...\n";
if ($administratorModel->validate()) {
    echo "   ✓ Formulaire valide\n\n";
    
    echo "2. Recherche de l'administrateur...\n";
    $administrator = Administrator::findOne(['username' => $administratorModel->username]);
    
    if ($administrator) {
        echo "   ✓ Administrateur trouvé (ID: {$administrator->id})\n";
        echo "   - user_id: {$administrator->user_id}\n\n";
        
        echo "3. Recherche de l'utilisateur...\n";
        $user = User::findOne($administrator->user_id);
        
        if ($user) {
            echo "   ✓ Utilisateur trouvé (ID: {$user->id})\n";
            echo "   - name: {$user->name}\n";
            echo "   - password hash: {$user->password}\n\n";
            
            echo "4. Validation du mot de passe...\n";
            if ($user->validatePassword($administratorModel->password)) {
                echo "   ✓ MOT DE PASSE VALIDE!\n\n";
                echo "=== CONNEXION RÉUSSIE ===\n";
            } else {
                echo "   ✗ Mot de passe invalide\n";
            }
        } else {
            echo "   ✗ Utilisateur non trouvé\n";
        }
    } else {
        echo "   ✗ Administrateur non trouvé\n";
    }
} else {
    echo "   ✗ Formulaire invalide\n";
    print_r($administratorModel->errors);
}
