<?php
// delete_all_except_members.php - Tout supprimer sauf les membres
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== SUPPRESSION DE TOUTES LES DONNÉES (SAUF MEMBRES) ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Désactiver les contraintes FK
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
        
        // Remettre à zéro les paiements des membres
        echo "\n🔄 Remise à zéro des paiements des membres...\n";
        $db->createCommand("UPDATE member SET inscription = 0, social_crown = 0, active = 0")->execute();
        
    } finally {
        echo "\n🔒 Réactivation des contraintes FK...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Toutes les données supprimées avec succès !\n";
    echo "✅ Les 12 membres sont conservés (inactifs, pas en règle)\n";
    echo "\n📝 Vous pouvez maintenant créer un nouvel exercice pour vos tests\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
