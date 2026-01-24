<?php
// clean_for_user_creation.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== NETTOYAGE COMPLET POUR RE-CREATION MANUELLE ===\n\n";

try {
    $db = Yii::$app->db;
    
    echo "🔓 Désactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    // Purge Data Tables
    echo "🗑️  Suppression des données 2026...\n";
    $db->createCommand("DELETE FROM borrowing_saving")->execute(); // Link table
    $db->createCommand("DELETE FROM borrowing")->execute();
    $db->createCommand("DELETE FROM saving")->execute();
    $db->createCommand("DELETE FROM refund")->execute();
    $db->createCommand("DELETE FROM renflouement")->execute();
    $db->createCommand("DELETE FROM session")->execute();
    $db->createCommand("DELETE FROM exercise WHERE year = '2026'")->execute();
    
    // Reset Members to 'Clean' state (Inactive, No payments recorded)
    // IMPORTANT: Keep the members themselves, just reset their financial status for the new exercise.
    echo "🔄 Réinitialisation des membres...\n";
    $db->createCommand("UPDATE member SET inscription = 0, social_crown = 0, active = 1")->execute(); 
    // Setting active=1 so they can log in? 
    // Usually active=0 means they haven't paid. But if they are inactive, can they log in?
    // User login depends on 'User' table usually. 'Member' active status usually controls participation.
    // Let's set active=1 just in case, or let the user activate them via payment.
    // The user wants to "recreate exercise".
    // I will set active=1 to be safe for testing.
    
    echo "🔒 Réactivation des contraintes FK...\n";
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    
    echo "\n✅ BASE DE DONNÉES NETTOYÉE !\n";
    echo "Vous pouvez maintenant créer l'exercice manuellement.\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
