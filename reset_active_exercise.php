<?php
// reset_active_exercise.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== RESET EXERCICE ACTIF ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Trouver l'exercice actif
    $exercise = $db->createCommand("SELECT * FROM exercise WHERE active = 1")->queryOne();
    
    // Si aucun exercice actif, trouver le dernier exercice créé par défaut
    if (!$exercise) {
         $exercise = $db->createCommand("SELECT * FROM exercise ORDER BY id DESC LIMIT 1")->queryOne();
    }
    
    if (!$exercise) {
        echo "❌ Aucun exercice trouvé à supprimer.\n";
        exit;
    }
    
    $exerciseId = $exercise['id'];
    $year = $exercise['year'];
    echo "✅ Exercice trouvé : {$year} (ID: {$exerciseId})\n\n";
    
    // Désactiver les contraintes FK
    echo "🔓 Désactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    try {
        // Trouver toutes les sessions de cet exercice
        $sessions = $db->createCommand("SELECT id FROM session WHERE exercise_id = {$exerciseId}")->queryAll();
        
        if (!empty($sessions)) {
            $sessionIds = array_column($sessions, 'id');
            $sessionIdsList = implode(',', $sessionIds);
            
            echo "🗑️  Suppression des données liées...\n";
            $db->createCommand("DELETE FROM renflouement WHERE next_exercise_id = {$exerciseId}")->execute();
            $db->createCommand("DELETE FROM refund WHERE session_id IN ({$sessionIdsList})")->execute();
            $db->createCommand("DELETE FROM borrowing WHERE session_id IN ({$sessionIdsList})")->execute();
            $db->createCommand("DELETE FROM saving WHERE session_id IN ({$sessionIdsList})")->execute();
            $db->createCommand("DELETE FROM agape WHERE session_id IN ({$sessionIdsList})")->execute();
            
            // Note: Help needs careful deletion if linked to session or just date
             $db->createCommand("DELETE FROM help WHERE created_at LIKE '{$year}%'")->execute();

            $db->createCommand("DELETE FROM session WHERE exercise_id = {$exerciseId}")->execute();
        }
        
        echo "🗑️  Suppression de l'exercice {$year}...\n";
        $db->createCommand("DELETE FROM exercise WHERE id = {$exerciseId}")->execute();
        
        // Remettre à zéro les paiements des membres
        echo "\n🔄 Remise à zéro des status membres...\n";
        $db->createCommand("UPDATE member SET inscription = 0, social_crown = 0, active = 0")->execute();
        
    } finally {
        $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    }
    
    echo "\n✅ Exercice vidé avec succès !\n";
    echo "Vous pouvez maintenant retourner sur l'interface Home pour créer un nouveau.\n";
    
} catch (Exception $e) {
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
