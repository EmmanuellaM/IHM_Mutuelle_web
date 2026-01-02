<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "<h1>Vidage Complet de la Base de Données</h1>";
echo "<p style='color:red'><b>⚠️ ATTENTION : Cette opération va supprimer TOUTES les données !</b></p>";
echo "<hr>";

$db = Yii::$app->db;

try {
    // Désactiver les contraintes de clés étrangères
    $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
    echo "<p>✓ Contraintes de clés étrangères désactivées</p>";
    
    // Liste des tables à vider (dans l'ordre pour éviter les conflits)
    $tables = [
        'renflouement',
        'social_fund',
        'registration',
        'contribution_tontine',
        'tontine',
        'contribution',
        'help',
        'agape',
        'refund',
        'borrowing_saving',
        'borrowing',
        'saving',
        'session',
        'exercise',
        'member',
        'administrator',
        'user',
        'help_type',
        'tontine_type',
        'chat_message'
    ];
    
    $deletedCounts = [];
    
    foreach ($tables as $table) {
        try {
            $count = $db->createCommand("SELECT COUNT(*) FROM `$table`")->queryScalar();
            $db->createCommand("TRUNCATE TABLE `$table`")->execute();
            $deletedCounts[$table] = $count;
            echo "<p>✓ Table <b>$table</b> : $count enregistrement(s) supprimé(s)</p>";
        } catch (Exception $e) {
            echo "<p style='color:orange'>⚠️ Table <b>$table</b> : " . $e->getMessage() . "</p>";
        }
    }
    
    // Réactiver les contraintes de clés étrangères
    $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    echo "<p>✓ Contraintes de clés étrangères réactivées</p>";
    
    echo "<hr>";
    echo "<h3 style='color:green'>✅ Base de données vidée avec succès !</h3>";
    
    $totalDeleted = array_sum($deletedCounts);
    echo "<p><b>Total : $totalDeleted enregistrements supprimés</b></p>";
    
    echo "<h4>Détails :</h4>";
    echo "<ul>";
    foreach ($deletedCounts as $table => $count) {
        if ($count > 0) {
            echo "<li>$table : $count</li>";
        }
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<p style='color:blue'><b>ℹ️ La structure de la base de données est intacte.</b></p>";
    echo "<p>Vous pouvez maintenant créer de nouveaux utilisateurs, exercices, etc.</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'><b>❌ Erreur : " . $e->getMessage() . "</b></p>";
    // Réactiver les contraintes même en cas d'erreur
    try {
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    } catch (Exception $e2) {
        // Ignore
    }
}
?>
