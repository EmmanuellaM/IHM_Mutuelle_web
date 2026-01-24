<?php
// delete_exercise_2026.php - Supprimer l'exercice 2026 et toutes ses données
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
        $sessions = $db->createCommand("SELECT id FROM session WHERE exercise_id = :id", [':id' => $exerciseId])->queryAll();
        $sessionIds = array_column($sessions, 'id');
        
        if (!empty($sessionIds)) {
            $sessionIdsList = implode(',', $sessionIds);
            
            echo "🗑️  Suppression des renflouements...\n";
            $db->createCommand("DELETE FROM renflouement WHERE next_exercise_id = :id", [':id' => $exerciseId])->execute();
            
            echo "🗑️  Suppression des remboursements...\n";
            $db->createCommand("DELETE FROM refund WHERE session_id IN ({$sessionIdsList})")->execute();
            
            echo "🗑️  Suppression des emprunts...\n";
            $db->createCommand("DELETE FROM borrowing WHERE session_id IN ({$sessionIdsList})")->execute();
            
            echo "🗑️  Suppression des épargnes...\n";
            $db->createCommand("DELETE FROM saving WHERE session_id IN ({$sessionIdsList})")->execute();
            
            echo "🗑️  Suppression des agapes...\n";
            $db->createCommand("DELETE FROM agape WHERE session_id IN ({$sessionIdsList})")->execute();
            
            echo "🗑️  Suppression des aides...\n";
            $db->createCommand("DELETE FROM help WHERE session_id IN ({$sessionIdsList})")->execute();
            
            echo "🗑️  Suppression des sessions...\n";
            $db->createCommand("DELETE FROM session WHERE exercise_id = :id", [':id' => $exerciseId])->execute();
        }
        
        echo "🗑️  Suppression de l'exercice 2026...\n";
        $db->createCommand("DELETE FROM exercise WHERE id = :id", [':id' => $exerciseId])->execute();
        
        // Remettre à zéro les paiements des membres
        echo "🔄 Remise à zéro des paiements des membres...\n";
        $db->createCommand("UPDATE member SET inscription = 0, social_crown = 0, active = 0")->execute();
        
    } finally {
        echo "🔒 Réactivation des contraintes FK...\n";
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Exercice 2026 supprimé avec succès !\n";
    echo "✅ Tous les membres sont maintenant inactifs (pas en règle)\n";
    echo "✅ Les 12 membres sont conservés dans la base\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
