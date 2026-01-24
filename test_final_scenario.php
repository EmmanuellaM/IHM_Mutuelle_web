<?php
// test_final_scenario.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;
use app\managers\FinanceManager;

echo "=== SCÉNARIO FINAL DE TEST ===\n\n";

// 1. NETTOYAGE & CRÉATION EXERCICE
echo "1. Initialisation...\n";

// Drop dependent data first (Bottom-up)
\app\models\Refund::deleteAll();
\app\models\BorrowingSaving::deleteAll(); // Link table
Saving::deleteAll();
Borrowing::deleteAll();
Session::deleteAll(); 
Exercise::deleteAll(['year' => 2026]);

$exercise = new Exercise();
$exercise->year = 2026;
$exercise->interest = 10; // 10% intérêt emprunt
$exercise->inscription_amount = 0;
$exercise->social_crown_amount = 0;
$exercise->penalty_rate = 5; // 5% pénalité
$exercise->active = true;
$exercise->administrator_id = 1; // Root
if (!$exercise->save()) {
    print_r($exercise->errors);
    die("Exercise save failed");
}
echo "   Exercice 2026 créé. ID: {$exercise->id}\n";
$exId = $exercise->id;

// 2. SESSION 1 (JANVIER) - EPARGNE & EMPRUNT
echo "\n2. Session 1 (Janvier) : Dépôt & Emprunt\n";
$s1 = new Session();
$s1->exercise_id = $exId;
$s1->date = '2026-01-01';
$s1->active = true;
if (!$s1->save()) {
    print_r($s1->errors);
    die("S1 Save failed");
} else {
    echo "   Session 1 sauvegardée (ID: {$s1->id})\n";
}

$member = Member::find()->one();
if (!$member) die("Aucun membre trouvé.\n");

// Reset member state
// $member->insoluble = false; // Property might not exist
$member->active = true;
$member->save(false);
Saving::deleteAll(['member_id' => $member->id]);
Borrowing::deleteAll(['member_id' => $member->id]);

// Epargne 100 000
$saving = new Saving();
$saving->member_id = $member->id;
$saving->session_id = $s1->id;
$saving->amount = 100000;
$saving->save();
echo "   Epargne : +100 000 (Solde: " . $member->savedAmount($exercise) . ")\n";

// Emprunt 100 000
$borrowing = new Borrowing();
$borrowing->member_id = $member->id;
$borrowing->session_id = $s1->id;
$borrowing->amount = 100000;
$borrowing->interest = 10; // 10%
$borrowing->state = true;
$borrowing->save();
echo "   Emprunt : 100 000 (Intérêt: 10%)\n";

// 3. SESSION 2 (FEVRIER) - Temps passe
echo "\n3. Session 2 (Février)\n";
$s2 = new Session();
$s2->exercise_id = $exercise->id;
$s2->date = '2026-02-01';
$s2->active = true;
$s2->save();
// Vérif pénalité (Ne doit PAS être là)
$penalties = Saving::find()->where(['member_id' => $member->id, 'amount' => -10000])->count();
echo "   Pénalités appliquées : $penalties (Attendu: 0)\n";

// 4. SESSION 3 (MARS) - DATE LIMITE
echo "\n4. Session 3 (Mars) - DERNIER DÉLAI\n";
$s3 = new Session();
$s3->exercise_id = $exercise->id;
$s3->date = '2026-03-01';
$s3->active = true;
$s3->save();
$penalties = Saving::find()->where(['member_id' => $member->id, 'amount' => -10000])->count();
echo "   Pénalités appliquées : $penalties (Attendu: 0)\n";

// 5. SESSION 4 (AVRIL) - DÉDUCTION AUTOMATIQUE
echo "\n5. Session 4 (Avril) - SANCTION\n";
$s4 = new Session();
$s4->exercise_id = $exercise->id;
$s4->date = '2026-04-01';
$s4->active = true;
$s4->save(); // Trigger afterSave logic

// Vérif Résultat
$penalty = Saving::find()->where(['member_id' => $member->id, 'amount' => -10000])->one();

if ($penalty) {
    echo "✅ SUCCÈS : Pénalité de 10 000 déduite en Avril.\n";
    echo "   Détails de la pénalité :\n";
    echo "   - Montant : " . $penalty->amount . "\n";
    echo "   - Session : " . $penalty->session->date() . "\n";
} else {
    echo "❌ ÉCHEC : Pas de pénalité en Avril.\n";
}

// ... (Existing Month 4 check)

// 6. SUITE : VERS LE 6ème MOIS
echo "\n6. Sessions 5 & 6 (Mai - Juin)\n";
// Mai
$s5 = new Session();
$s5->exercise_id = $exId;
$s5->date = '2026-05-01';
$s5->active = true;
$s5->save();

// Juin
$s6 = new Session();
$s6->exercise_id = $exId;
$s6->date = '2026-06-01';
$s6->active = true;
$s6->save();

echo "   Avance jusqu'à Juin...\n";

// 7. SESSION 7 (JUILLET) - 6 MOIS DE RETARD
echo "\n7. Session 7 (Juillet) - 6 MOIS DE RETARD\n";
$s7 = new Session();
$s7->exercise_id = $exId;
$s7->date = '2026-07-01';
$s7->active = true;
$s7->save(); // Trigger afterSave logic for 6 months

// Check for Penalty 'm'
// We expect a NEW penalty at Session 7.
// Total bad savings should be 2 (One from Month 4, One from Month 7).
// Note: Month 4 penalty amount was Interest (10000).
// Month 7 penalty amount is Rate 'm' (5% of 100k = 5000).

$penaltyM = Saving::find()
    ->where(['member_id' => $member->id])
    ->andWhere(['session_id' => $s7->id])
    ->andWhere(['<', 'amount', 0])
    ->one();

if ($penaltyM) {
     echo "✅ SUCCÈS : Pénalité 'm' appliquée en Juillet.\n";
     echo "   Montant : " . $penaltyM->amount . " (Attendu: -5000 car 5%)\n";
} else {
     echo "❌ ÉCHEC : Pas de pénalité 'm' en Juillet.\n";
}

// Check Insolvency?
$member->refresh();
// echo "   Membre Insoluble ? " . ($member->insoluble ? 'OUI' : 'NON') . "\n";
echo "   (Note: Le système actuel à 6 mois envoie une ALERTE, mais ne rend pas le membre insoluble automatiquement sauf si l'admin intervient).\n";

echo "\n--- FIN DU TEST ---\n";

