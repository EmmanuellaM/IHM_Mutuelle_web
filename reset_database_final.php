<?php
// reset_database_final.php - Version finale du reset
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== RESET COMPLET DE LA BASE DE DONNÉES ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Désactiver les contraintes
    echo "🔓 Désactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    try {
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
        
        // Garder seulement root
        echo "🗑️  Suppression des utilisateurs (sauf root)...\n";
        $rootUser = $db->createCommand("SELECT * FROM user WHERE name = 'root' OR first_name = 'root' LIMIT 1")->queryOne();
        
        if ($rootUser) {
            $db->createCommand("DELETE FROM user WHERE id != :id", [':id' => $rootUser['id']])->execute();
            $db->createCommand("DELETE FROM administrator WHERE id != :id", [':id' => $rootUser['id']])->execute();
        } else {
            echo "⚠️  Utilisateur root non trouvé, conservation de tous les admins\n";
        }
        
    } finally {
        echo "🔒 Réactivation des contraintes FK...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Base de données réinitialisée avec succès !\n";
    echo "✅ Compte admin conservé : root / root\n";
    echo "\n🎯 Vous pouvez maintenant recommencer vos tests à zéro !\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
