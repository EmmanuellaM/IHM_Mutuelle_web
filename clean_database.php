<?php

/**
 * Script de nettoyage de la base de données
 * Supprime les membres de test, sessions et aides
 * Préserve l'administrateur et les types d'aide
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

echo "=== Script de Nettoyage de la Base de Données ===\n\n";

try {
    $db = Yii::$app->db;
    $transaction = $db->beginTransaction();
    
    echo "Début du nettoyage...\n\n";
    
    // 1. Supprimer les renflouements
    echo "1. Suppression des renflouements...\n";
    $count = $db->createCommand('DELETE FROM renflouement')->execute();
    echo "   ✓ {$count} renflouement(s) supprimé(s)\n\n";
    
    // 2. Supprimer les aides
    echo "2. Suppression des aides...\n";
    $count = $db->createCommand('DELETE FROM help')->execute();
    echo "   ✓ {$count} aide(s) supprimée(s)\n\n";
    
    // 3. Supprimer les remboursements
    echo "3. Suppression des remboursements...\n";
    $count = $db->createCommand('DELETE FROM refund')->execute();
    echo "   ✓ {$count} remboursement(s) supprimé(s)\n\n";
    
    // 4. Supprimer les épargnes empruntées (borrowing_saving)
    echo "4. Suppression des épargnes empruntées...\n";
    $count = $db->createCommand('DELETE FROM borrowing_saving')->execute();
    echo "   ✓ {$count} épargne(s) empruntée(s) supprimée(s)\n\n";
    
    // 5. Supprimer les emprunts
    echo "5. Suppression des emprunts...\n";
    $count = $db->createCommand('DELETE FROM borrowing')->execute();
    echo "   ✓ {$count} emprunt(s) supprimé(s)\n\n";
    
    // 6. Supprimer les épargnes
    echo "6. Suppression des épargnes...\n";
    $count = $db->createCommand('DELETE FROM saving')->execute();
    echo "   ✓ {$count} épargne(s) supprimée(s)\n\n";
    
    // 7. Supprimer les cotisations tontine
    echo "7. Suppression des cotisations tontine...\n";
    $count = $db->createCommand('DELETE FROM contribution_tontine')->execute();
    echo "   ✓ {$count} cotisation(s) tontine supprimée(s)\n\n";
    
    // 8. Supprimer les tontines
    echo "8. Suppression des tontines...\n";
    $count = $db->createCommand('DELETE FROM tontine')->execute();
    echo "   ✓ {$count} tontine(s) supprimée(s)\n\n";
    
    // 9. Supprimer les cotisations
    echo "9. Suppression des cotisations...\n";
    $count = $db->createCommand('DELETE FROM contribution')->execute();
    echo "   ✓ {$count} cotisation(s) supprimée(s)\n\n";
    
    // 10. Supprimer les agapes
    echo "10. Suppression des agapes...\n";
    $count = $db->createCommand('DELETE FROM agape')->execute();
    echo "   ✓ {$count} agape(s) supprimée(s)\n\n";
    
    // Vérifier si la table agape3 existe avant de la supprimer
    try {
        $count = $db->createCommand('DELETE FROM agape3')->execute();
        echo "   ✓ {$count} agape3(s) supprimée(s)\n\n";
    } catch (Exception $e) {
        echo "   ℹ Table agape3 non trouvée (ignorée)\n\n";
    }
    
    // 11. Supprimer les sessions
    echo "11. Suppression des sessions...\n";
    $count = $db->createCommand('DELETE FROM session')->execute();
    echo "   ✓ {$count} session(s) supprimée(s)\n\n";
    
    // 12. Supprimer les exercices
    echo "12. Suppression des exercices...\n";
    $count = $db->createCommand('DELETE FROM exercise')->execute();
    echo "   ✓ {$count} exercice(s) supprimé(s)\n\n";
    
    // 13. Supprimer les membres (mais pas les administrateurs)
    echo "13. Suppression des membres...\n";
    
    // D'abord, récupérer les IDs des utilisateurs membres
    $memberUserIds = $db->createCommand('SELECT user_id FROM member')->queryColumn();
    
    // Supprimer les membres
    $count = $db->createCommand('DELETE FROM member')->execute();
    echo "   ✓ {$count} membre(s) supprimé(s)\n";
    
    // Supprimer les utilisateurs correspondants (seulement ceux de type MEMBER)
    if (!empty($memberUserIds)) {
        $count = $db->createCommand()
            ->delete('user', ['and', ['id' => $memberUserIds], ['type' => 'MEMBER']])
            ->execute();
        echo "   ✓ {$count} utilisateur(s) membre(s) supprimé(s)\n\n";
    } else {
        echo "   ✓ 0 utilisateur(s) membre(s) supprimé(s)\n\n";
    }
    
    // Commit de la transaction
    $transaction->commit();
    
    echo "===========================================\n";
    echo "✅ Nettoyage terminé avec succès !\n";
    echo "===========================================\n\n";
    
    // Afficher un résumé de ce qui reste
    echo "📊 Résumé de la base de données :\n";
    echo "   - Administrateurs : " . $db->createCommand('SELECT COUNT(*) FROM administrator')->queryScalar() . "\n";
    echo "   - Types d'aide : " . $db->createCommand('SELECT COUNT(*) FROM help_type')->queryScalar() . "\n";
    echo "   - Types de tontine : " . $db->createCommand('SELECT COUNT(*) FROM tontine_type')->queryScalar() . "\n";
    echo "   - Membres : " . $db->createCommand('SELECT COUNT(*) FROM member')->queryScalar() . "\n";
    echo "   - Sessions : " . $db->createCommand('SELECT COUNT(*) FROM session')->queryScalar() . "\n";
    echo "   - Exercices : " . $db->createCommand('SELECT COUNT(*) FROM exercise')->queryScalar() . "\n";
    echo "   - Aides : " . $db->createCommand('SELECT COUNT(*) FROM help')->queryScalar() . "\n";
    
} catch (Exception $e) {
    if (isset($transaction)) {
        $transaction->rollBack();
    }
    echo "\n❌ ERREUR lors du nettoyage :\n";
    echo $e->getMessage() . "\n";
    echo "\nLa transaction a été annulée. Aucune modification n'a été appliquée.\n";
    exit(1);
}
