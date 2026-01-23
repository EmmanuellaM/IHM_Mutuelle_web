<?php
// reset_database.php - Vider toutes les données pour recommencer les tests
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== RESET COMPLET DE LA BASE DE DONNÉES ===\n\n";
echo "⚠️  ATTENTION : Cette opération va supprimer TOUTES les données !\n";
echo "Seul le compte administrateur (root/root) sera conservé.\n\n";

try {
    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();
    
    try {
        // 1. Supprimer les renflouements
        echo "🗑️  Suppression des renflouements...\n";
        $db->createCommand("DELETE FROM renflouement")->execute();
        
        // 2. Supprimer les remboursements
        echo "🗑️  Suppression des remboursements...\n";
        $db->createCommand("DELETE FROM refund")->execute();
        
        // 3. Supprimer les emprunts
        echo "🗑️  Suppression des emprunts...\n";
        $db->createCommand("DELETE FROM borrowing")->execute();
        
        // 4. Supprimer les épargnes
        echo "🗑️  Suppression des épargnes...\n";
        $db->createCommand("DELETE FROM saving")->execute();
        
        // 5. Supprimer les agapes
        echo "🗑️  Suppression des agapes...\n";
        $db->createCommand("DELETE FROM agape")->execute();
        
        // 6. Supprimer les aides
        echo "🗑️  Suppression des aides...\n";
        $db->createCommand("DELETE FROM help")->execute();
        
        // 7. Supprimer les sessions
        echo "🗑️  Suppression des sessions...\n";
        $db->createCommand("DELETE FROM session")->execute();
        
        // 8. Supprimer les exercices
        echo "🗑️  Suppression des exercices...\n";
        $db->createCommand("DELETE FROM exercise")->execute();
        
        // 9. Supprimer les membres (sauf admin)
        echo "🗑️  Suppression des membres...\n";
        $db->createCommand("DELETE FROM member")->execute();
        
        // 10. Supprimer les utilisateurs (sauf root)
        echo "🗑️  Suppression des utilisateurs (sauf root)...\n";
        $db->createCommand("DELETE FROM user WHERE username != 'root'")->execute();
        
        // 11. Supprimer les administrateurs (sauf root)
        echo "🗑️  Suppression des administrateurs (sauf root)...\n";
        $adminRoot = $db->createCommand("SELECT id FROM user WHERE username = 'root'")->queryScalar();
        if ($adminRoot) {
            $db->createCommand("DELETE FROM administrator WHERE id != :id", [':id' => $adminRoot])->execute();
        }
        
        $transaction->commit();
        
        echo "\n✅ Base de données réinitialisée avec succès !\n";
        echo "✅ Compte admin conservé : root / root\n";
        echo "\n🎯 Vous pouvez maintenant recommencer vos tests à zéro !\n";
        
    } catch (Exception $e) {
        $transaction->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
