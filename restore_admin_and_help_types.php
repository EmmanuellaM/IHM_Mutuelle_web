<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "<h1>Restauration de l'Administrateur et des Types d'Aide</h1>";
echo "<hr>";

$db = Yii::$app->db;

try {
    $hashedPassword = Yii::$app->security->generatePasswordHash('root');
    
    // 1. Créer l'utilisateur admin
    $db->createCommand()->insert('user', [
        'name' => 'Admin',
        'first_name' => 'System',
        'email' => 'admin@mutuelle.com',
        'password' => $hashedPassword,
        'type' => 'ADMINISTRATOR',
    ])->execute();
    
    $userId = $db->getLastInsertID();
    echo "<p>✓ Utilisateur admin créé (ID: $userId)</p>";
    
    // 2. Créer l'administrateur
    $db->createCommand()->insert('administrator', [
        'user_id' => $userId,
        'username' => 'root',
    ])->execute();
    
    echo "<p>✓ Administrateur créé (Username: root, Password: root)</p>";
    
    echo "<hr>";
    
    // 3. Créer les types d'aide
    $helpTypes = [
        ['title' => 'Décès', 'amount' => 50000],
        ['title' => 'Mariage', 'amount' => 30000],
        ['title' => 'Naissance', 'amount' => 20000],
        ['title' => 'Maladie', 'amount' => 25000],
        ['title' => 'Accident', 'amount' => 40000],
        ['title' => 'Autre', 'amount' => 15000],
    ];
    
    foreach ($helpTypes as $typeData) {
        $db->createCommand()->insert('help_type', $typeData)->execute();
        echo "<p>✓ Type d'aide créé : <b>{$typeData['title']}</b> - {$typeData['amount']} XAF</p>";
    }
    
    echo "<hr>";
    echo "<h3 style='color:green'>✅ Restauration terminée !</h3>";
    echo "<div style='background:#f0f0f0; padding:15px; border-radius:5px; margin-top:20px'>";
    echo "<h4>Informations de connexion :</h4>";
    echo "<p><b>Username :</b> root</p>";
    echo "<p><b>Password :</b> root</p>";
    echo "<p><b>Email :</b> admin@mutuelle.com</p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p style='color:red'><b>❌ Erreur : " . $e->getMessage() . "</b></p>";
}
?>
