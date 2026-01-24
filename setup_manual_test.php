<?php
// setup_manual_test.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "=== PREPARATION POUR TEST MANUEL ===\n\n";

// 1. NETTOYAGE ROBUSTE
echo "1. Nettoyage des données existantes pour 2026...\n";
\app\models\Refund::deleteAll();
\app\models\BorrowingSaving::deleteAll(); 
Saving::deleteAll();
Borrowing::deleteAll();
Session::deleteAll(); 
Exercise::deleteAll(['year' => 2026]);

// 2. CRÉATION EXERCICE
$exercise = new Exercise();
$exercise->year = 2026;
$exercise->interest = 10; // 10% intérêt emprunt
$exercise->inscription_amount = 0;
$exercise->social_crown_amount = 0;
$exercise->penalty_rate = 5; // 5% pénalité (Taux m)
$exercise->active = true;
$exercise->administrator_id = 1; // Root
if (!$exercise->save()) {
    print_r($exercise->errors);
    die("Erreur Création Exercice");
}
echo "✅ Exercice 2026 créé (ID: {$exercise->id}).\n";

// 3. CRÉATION SESSION 1
try {
    // Verify Exercise exists
    $check = Exercise::findOne($exercise->id);
    if (!$check) die("Exercise non trouvé en DB après save!");

    $s1 = new Session();
    $s1->exercise_id = $exercise->id;
    $s1->administrator_id = 1;
    $s1->date = '2026-01-01';
    $s1->active = true; 
    
    if (!$s1->save()) {
        print_r($s1->errors);
        die("Erreur Création Session 1");
    }
    echo "✅ Session 1 (Janvier) créée.\n";
} catch (\Exception $e) {
    die("Exception Session 1: " . $e->getMessage() . "\n" . $e->getTraceAsString());
}

// 4. PRÉPARATION MEMBRE (Dépôt + Emprunt)
$member = Member::find()->one();
if (!$member) die("Aucun membre trouvé.");
// Reset
//$member->insoluble = false; 
$member->active = true;
$member->save(false);

// Epargne 100 000
$saving = new Saving();
$saving->member_id = $member->id;
$saving->session_id = $s1->id;
$saving->amount = 100000;
$saving->save();
echo "✅ Member {$member->id}: Epargne +100 000 déposée.\n";

// Emprunt 100 000
$borrowing = new Borrowing();
$borrowing->member_id = $member->id;
$borrowing->session_id = $s1->id;
$borrowing->amount = 100000;
$borrowing->interest = 10; // 10%
$borrowing->state = true;
if ($borrowing->save()) {
    echo "✅ Member {$member->id}: Emprunt 100 000 contracté.\n";
} else {
    print_r($borrowing->errors);
}

echo "\n--- PRET POUR VOTRE TEST ---\n";
echo "Vous pouvez maintenant aller sur l'interface et créer les sessions suivantes :\n";
echo "- Session 2 (Février)\n";
echo "- Session 3 (Mars)\n";
echo "- Session 4 (Avril) -> C'est ici que la pénalité doit tomber !\n";
