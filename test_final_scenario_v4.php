<?php
// test_final_scenario_v4.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "\n🛠️  INITIALISATION DU TEST FINAL (SCENARIO 'GRIGNOTAGE') 🛠️\n";
echo "=========================================================\n";

// 1. CLEANUP RAPIDE
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
Borrowing::deleteAll(); Saving::deleteAll(); Session::deleteAll(); Exercise::deleteAll(['year' => 2026]);
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();

// 2. SETUP EXERCICE
$ex = new Exercise(['year' => 2026, 'interest' => 3, 'penalty_rate' => 5, 'active' => true, 'inscription_amount' => 1000, 'social_crown_amount' => 150000]);
$ex->save();
echo "✅ Exercice 2026 créé (Intérêt: 3%, Taux m: 5%).\n";

// 3. SESSION 1 (Janvier) - Départ
$s1 = new Session(['exercise_id' => $ex->id, 'date' => '2026-01-01', 'administrator_id' => 1]); $s1->save();

$m1 = Member::find()->limit(1)->one(); // JEAN (Le limite)
$m2 = Member::find()->limit(1)->offset(1)->one(); // PAUL (Le riche)

// --- CONFIGURATION INITIALE ---
// JEAN: 21 000 Epargne. Capacité = 105 000. Emprunt = 100 000. (Marge = 5 000)
$sav1 = new Saving(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 21000, 'administrator_id' => 1]); $sav1->save();
$bor1 = new Borrowing(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]); $bor1->save();

// PAUL: 50 000 Epargne. Capacité = 250 000. Emprunt = 100 000. (Marge = 150 000)
$sav2 = new Saving(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 50000, 'administrator_id' => 1]); $sav2->save();
$bor2 = new Borrowing(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]); $bor2->save();

echo "\n📊 ETAT DE DEPART (Janvier):\n";
echo "   - JEAN: Epargne 21 000. Capacité (x5) = 105 000. Dette = 100 000. -> SOLVABLE (Marge +5000).\n";
echo "   - PAUL: Epargne 50 000. Capacité (x5) = 250 000. Dette = 100 000. -> SOLVABLE (Marge +150k).\n";

// --- MOIS 2 & 3 (Rien) ---
(new Session(['exercise_id' => $ex->id, 'date' => '2026-02-01']))->save();
(new Session(['exercise_id' => $ex->id, 'date' => '2026-03-01']))->save();

// --- MOIS 4 (Avril) - PREMIER CONTROLE ---
echo "\n⏱️  PASSAGE AU MOIS 4 (Avril) - 1er Prélèvement...\n";
$s4 = new Session(['exercise_id' => $ex->id, 'date' => '2026-04-01', 'administrator_id' => 1]); $s4->save();

// Vérifions ce qui s'est passé
echo "   --- Résultats Mois 4 ---\n";
checkResult($m1, $s4, 100000, "JEAN");
checkResult($m2, $s4, 100000, "PAUL");

// --- MOIS 5 & 6 (Rien) ---
(new Session(['exercise_id' => $ex->id, 'date' => '2026-05-01']))->save();
(new Session(['exercise_id' => $ex->id, 'date' => '2026-06-01']))->save();

// --- MOIS 7 (Juillet) - SECOND CONTROLE (Le Piège) ---
echo "\n⏱️  PASSAGE AU MOIS 7 (Juillet) - 2ème Prélèvement (Le Piège)...\n";
$s7 = new Session(['exercise_id' => $ex->id, 'date' => '2026-07-01', 'administrator_id' => 1]); $s7->save();

echo "   --- Résultats Mois 7 ---\n";
checkResult($m1, $s7, 100000, "JEAN");
checkResult($m2, $s7, 100000, "PAUL");


// FUNCTION HELPER
function checkResult($member, $session, $debt, $name) {
    // Check deducation in THIS session
    $deduction = Saving::find()->where(['member_id' => $member->id, 'session_id' => $session->id])->andWhere(['<', 'amount', 0])->sum('amount');
    
    // Check Totals
    $totalSavings = Saving::find()->where(['member_id' => $member->id])->sum('amount');
    $capacity = $totalSavings * 5;
    
    echo "   👤 $name :\n";
    echo "      - Déduction ce mois : " . ($deduction ? $deduction : "0") . "\n";
    echo "      - Nouvelle Epargne  : $totalSavings\n";
    echo "      - Nouvelle Capacité : $capacity (vs Dette $debt)\n";
    
    if ($deduction == -3000) echo "      => Payer INTERET STANDARD (Solvable).\n";
    elseif ($deduction == -5000) echo "      => Payer PENALITE M (Insolvable !).\n";
    elseif ($deduction == 0) echo "      => RIEN (Bizarre ?).\n";
    else echo "      => Autre montant ($deduction).\n";
}
