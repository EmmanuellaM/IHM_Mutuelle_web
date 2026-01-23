<?php
// reset_database_v2.php - Vider toutes les données (version avec désactivation des FK)
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== RESET COMPLET DE LA BASE DE DONNÉES ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Désactiver les contraintes de clés étrangères
    echo "🔓 Désactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    try {
        // Supprimer dans l'ordre inverse des dépendances
        echo "🗑️  Suppression des renflouements...\n";
        $db->createCommand("TRUNCATE TABLE renflouement")->execute();
        
        echo "🗑️  Suppression des remboursements...\n";
        $db->createCommand("TRUNCATE TABLE refund")->execute();
        
        echo "🗑️  Suppression des emprunts...\n";
        $db->createCommand("TRUNCATE TABLE borrowing")->execute();
        
        echo "🗑️  Suppression des épargnes...\n";
        $db->createCommand("TRUNCATE TABLE saving")->execute();
        
        echo "🗑️  Suppression des agapes...\n";
        $db->createCommand("TRUNCATE TABLE agape")->execute();
        
        echo "🗑️  Suppression des aides...\n";
        $db->createCommand("TRUNCATE TABLE help")->execute();
        
        echo "🗑️  Suppression des sessions...\n";
        $db->createCommand("TRUNCATE TABLE session")->execute();
        
        echo "🗑️  Suppression des exercices...\n";
        $db->createCommand("TRUNCATE TABLE exercise")->execute();
        
        echo "🗑️  Suppression des membres...\n";
        $db->createCommand("TRUNCATE TABLE member")->execute();
        
        echo "🗑️  Suppression des utilisateurs (sauf root)...\n";
        $db->createCommand("DELETE FROM user WHERE username != 'root'")->execute();
        
        echo "🗑️  Suppression des administrateurs (sauf root)...\n";
        $adminRoot = $db->createCommand("SELECT id FROM user WHERE username = 'root'")->queryScalar();
        if ($adminRoot) {
            $db->createCommand("DELETE FROM administrator WHERE id != :id", [':id' => $adminRoot])->execute();
        }
        
    } finally {
        // Réactiver les contraintes
        echo "🔒 Réactivation des contraintes FK...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Base de données réinitialisée avec succès !\n";
    echo "✅ Compte admin conservé : root / root\n";
    echo "\n🎯 Vous pouvez maintenant recommencer vos tests à zéro !\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
