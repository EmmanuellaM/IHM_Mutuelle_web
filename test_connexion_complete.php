<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/web.php';
new yii\web\Application($config);

use app\models\Administrator;
use app\models\User;
use app\models\forms\AdministratorConnectionForm;

echo "=== TEST COMPLET DE CONNEXION ADMIN ===\n\n";

// Test 1: Vérifier que l'admin existe
echo "1. Vérification de l'administrateur 'admin':\n";
$admin = Administrator::findOne(['username' => 'admin']);
if ($admin) {
    echo "   ✓ Administrateur trouvé (ID: {$admin->id}, user_id: {$admin->user_id})\n";
} else {
    echo "   ✗ Administrateur 'admin' non trouvé!\n";
    exit(1);
}

// Test 2: Vérifier l'utilisateur
echo "\n2. Vérification de l'utilisateur:\n";
$user = User::findOne($admin->user_id);
if ($user) {
    echo "   ✓ Utilisateur trouvé (ID: {$user->id})\n";
    echo "   - name: {$user->name}\n";
    echo "   - type: {$user->type}\n";
} else {
    echo "   ✗ Utilisateur non trouvé!\n";
    exit(1);
}

// Test 3: Tester la validation du mot de passe
echo "\n3. Test de validation du mot de passe:\n";
$passwords = ['admin123', 'admin', 'root', 'password'];
foreach ($passwords as $pwd) {
    $isValid = $user->validatePassword($pwd);
    echo "   " . ($isValid ? "✓" : "✗") . " Mot de passe '$pwd': " . ($isValid ? "VALIDE" : "invalide") . "\n";
}

// Test 4: Simuler le processus de connexion complet
echo "\n4. Simulation du processus de connexion:\n";
$form = new AdministratorConnectionForm();
$form->username = 'admin';
$form->password = 'admin123';
$form->remember = false;

echo "   - Validation du formulaire...\n";
if ($form->validate()) {
    echo "   ✓ Formulaire valide\n";
    
    $administrator = Administrator::findOne(['username' => $form->username]);
    if ($administrator) {
        echo "   ✓ Administrateur trouvé\n";
        
        $user = User::findOne($administrator->user_id);
        if ($user && $user->validatePassword($form->password)) {
            echo "   ✓ Mot de passe valide\n";
            echo "\n=== CONNEXION DEVRAIT RÉUSSIR ===\n";
        } else {
            echo "   ✗ Mot de passe invalide\n";
        }
    } else {
        echo "   ✗ Administrateur non trouvé\n";
    }
} else {
    echo "   ✗ Formulaire invalide\n";
    print_r($form->errors);
}
