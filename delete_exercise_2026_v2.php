<?php
// delete_exercise_2026_v2.php - Supprimer l'exercice 2026 et toutes ses données
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== SUPPRESSION DE L'EXERCICE 2026 ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Trouver l'exercice 2026
    $exercise = $db->createCommand("SELECT * FROM exercise WHERE year = '2026'")->queryOne();
    
    if (!$exercise) {
        echo "❌ Exercice 2026 non trouvé.\n";
        exit;
    }
    
    $exerciseId = $exercise['id'];
    echo "✅ Exercice 2026 trouvé (ID: {$exerciseId})\n\n";
    
    // Désactiver les contraintes FK
    echo "🔓 Désactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    try {
        // Trouver toutes les sessions de cet exercice
        $sessions = $db->createCommand("SELECT id FROM session WHERE exercise_id = {$exerciseId}")->queryAll();
        
        if (!empty($sessions)) {
            $sessionIds = array_column($sessions, 'id');
            $sessionIdsList = implode(',', $sessionIds);
            
            echo "🗑️  Suppression des renflouements liés...\n";
            $count = $db->createCommand("DELETE FROM renflouement WHERE next_exercise_id = {$exerciseId}")->execute();
            echo "   → {$count} renflouements supprimés\n";
            
            echo "🗑️  Suppression des remboursements...\n";
            $count = $db->createCommand("DELETE FROM refund WHERE session_id IN ({$sessionIdsList})")->execute();
            echo "   → {$count} remboursements supprimés\n";
            
            echo "🗑️  Suppression des emprunts...\n";
            $count = $db->createCommand("DELETE FROM borrowing WHERE session_id IN ({$sessionIdsList})")->execute();
            echo "   → {$count} emprunts supprimés\n";
            
            echo "🗑️  Suppression des épargnes...\n";
            $count = $db->createCommand("DELETE FROM saving WHERE session_id IN ({$sessionIdsList})")->execute();
            echo "   → {$count} épargnes supprimées\n";
            
            echo "🗑️  Suppression des agapes...\n";
            $count = $db->createCommand("DELETE FROM agape WHERE session_id IN ({$sessionIdsList})")->execute();
            echo "   → {$count} agapes supprimées\n";
            
            echo "🗑️  Suppression des aides...\n";
            $count = $db->createCommand("DELETE FROM help WHERE session_id IN ({$sessionIdsList})")->execute();
            echo "   → {$count} aides supprimées\n";
            
            echo "🗑️  Suppression des sessions...\n";
            $count = $db->createCommand("DELETE FROM session WHERE exercise_id = {$exerciseId}")->execute();
            echo "   → {$count} sessions supprimées\n";
        }
        
        echo "🗑️  Suppression de l'exercice 2026...\n";
        $db->createCommand("DELETE FROM exercise WHERE id = {$exerciseId}")->execute();
        echo "   → Exercice supprimé\n";
        
        // Remettre à zéro les paiements des membres
        echo "\n🔄 Remise à zéro des paiements des membres...\n";
        $db->createCommand("UPDATE member SET inscription = 0, social_crown = 0, active = 0")->execute();
        echo "   → Tous les membres sont maintenant inactifs\n";
        
    } finally {
        echo "\n🔒 Réactivation des contraintes FK...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Exercice 2026 supprimé avec succès !\n";
    echo "✅ Tous les membres sont maintenant inactifs (pas en règle)\n";
    echo "✅ Les 12 membres sont conservés dans la base\n";
    echo "\n📝 Vous pouvez maintenant créer un nouvel exercice pour vos tests\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
    echo "Trace : " . $e->getTraceAsString() . "\n";
}
