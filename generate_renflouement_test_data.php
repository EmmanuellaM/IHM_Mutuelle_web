<?php
/**
 * Script de génération de données de test pour le renflouement
 * 
 * Ce script crée :
 * - 12 sessions pour l'exercice actif
 * - 1 aide financière dans la première session
 * 
 * Usage: php generate_renflouement_test_data.php
 */

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
$application = new yii\web\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Help;
use app\models\HelpType;
use app\models\Member;
use app\models\Administrator;

echo "=== Génération de données de test pour le renflouement ===\n\n";

// 1. Vérifier qu'il y a un exercice actif
$exercise = Exercise::findOne(['active' => true]);
if (!$exercise) {
    echo "❌ Erreur : Aucun exercice actif trouvé.\n";
    echo "Veuillez créer un exercice actif avant d'exécuter ce script.\n";
    exit(1);
}

echo "✅ Exercice actif trouvé\n";
echo "   ID: {$exercise->id}\n";
echo "   Année: {$exercise->year}\n\n";

// 2. Supprimer les sessions existantes pour cet exercice (pour un test propre)
$existingSessions = Session::find()->where(['exercise_id' => $exercise->id])->all();
if (count($existingSessions) > 0) {
    echo "⚠️  Suppression de " . count($existingSessions) . " session(s) existante(s)...\n";
    foreach ($existingSessions as $session) {
        $session->delete();
    }
    echo "✅ Sessions supprimées\n\n";
}

// 3. Récupérer un administrateur pour les créations
$admin = Administrator::find()->one();
if (!$admin) {
    echo "❌ Erreur : Aucun administrateur trouvé.\n";
    exit(1);
}

echo "✅ Administrateur ID: {$admin->id}\n\n";

// 4. Créer 12 sessions
echo "📅 Création de 12 sessions...\n";
$sessions = [];
$startDate = new DateTime(); // Utiliser la date actuelle

for ($i = 1; $i <= 12; $i++) {
    $session = new Session();
    $session->exercise_id = $exercise->id;
    $session->date = $startDate->format('Y-m-d');
    $session->active = ($i === 1); // Seule la première session est active
    $session->administrator_id = $admin->id;
    
    if ($session->save()) {
        $sessions[] = $session;
        echo "   ✓ Session {$i} créée (Date: {$session->date})\n";
    } else {
        echo "   ✗ Erreur lors de la création de la session {$i}\n";
        print_r($session->errors);
    }
    
    // Avancer d'un mois pour la prochaine session
    $startDate->modify('+1 month');
}

$sessionCount = count($sessions);
echo "\n✅ {$sessionCount} sessions créées avec succès\n\n";

// 5. Vérifier qu'il y a des membres
$members = Member::find()->where(['active' => true])->all();
if (count($members) === 0) {
    echo "❌ Erreur : Aucun membre actif trouvé.\n";
    echo "Veuillez créer au moins un membre avant d'exécuter ce script.\n";
    exit(1);
}

echo "✅ " . count($members) . " membre(s) actif(s) trouvé(s)\n\n";

// 6. Créer un type d'aide si nécessaire
$helpType = HelpType::find()->one();
if (!$helpType) {
    echo "📝 Création d'un type d'aide...\n";
    $helpType = new HelpType();
    $helpType->title = "Aide de test pour renflouement";
    $helpType->amount = 50000; // 50,000 XAF
    $helpType->description = "Type d'aide créé pour tester le renflouement";
    
    if ($helpType->save()) {
        echo "✅ Type d'aide créé : {$helpType->title} ({$helpType->amount} XAF)\n\n";
    } else {
        echo "❌ Erreur lors de la création du type d'aide\n";
        print_r($helpType->errors);
        exit(1);
    }
} else {
    echo "✅ Type d'aide existant : {$helpType->title} ({$helpType->amount} XAF)\n\n";
}

// 7. Créer une aide dans la première session
echo "🆘 Création d'une aide financière dans la session 1...\n";

// Prendre le premier membre
$member = $members[0];
$firstSession = $sessions[0];

$help = new Help();
$help->member_id = $member->id;
$help->help_type_id = $helpType->id;
$help->amount = 0; // Montant collecté initialement à 0
$help->unit_amount = ceil($helpType->amount / count($members)); // Montant par membre
$help->administrator_id = $admin->id;

if ($help->save()) {
    echo "✅ Aide créée avec succès !\n";
    echo "   Bénéficiaire ID: {$member->id}\n";
    echo "   Montant cible : {$helpType->amount} XAF\n";
    echo "   Contribution par membre : {$help->unit_amount} XAF\n";
} else {
    echo "❌ Erreur lors de la création de l'aide\n";
    print_r($help->errors);
    exit(1);
}

echo "\n";
echo "========================================\n";
echo "✅ GÉNÉRATION TERMINÉE AVEC SUCCÈS !\n";
echo "========================================\n\n";

echo "📊 Résumé :\n";
echo "   - Exercice actif : Année {$exercise->year}\n";
echo "   - Sessions créées : 12\n";
echo "   - Session active : Session 1\n";
echo "   - Aide créée : 1 (dans la session 1)\n";
echo "   - Bénéficiaire ID : {$member->id}\n\n";

echo "🔄 Prochaines étapes pour tester le renflouement :\n";
echo "   1. Connectez-vous en tant qu'administrateur\n";
echo "   2. Allez dans 'Exercices' et clôturez l'exercice actif\n";
echo "   3. Le système devrait créer automatiquement les renflouements\n";
echo "   4. Vérifiez dans 'Renflouements' que les données sont correctes\n\n";

echo "✨ Script terminé !\n";
