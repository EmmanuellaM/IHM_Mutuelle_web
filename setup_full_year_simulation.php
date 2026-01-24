<?php
// setup_full_year_simulation.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "\n🗓️ GENERATION SIMULATION 12 MOIS (GRIGNOTAGE) 🗓️\n";
echo "================================================\n";

// 1. CLEANUP
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
Borrowing::deleteAll(); Saving::deleteAll(); Session::deleteAll(); Exercise::deleteAll(['year' => 2026]);
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();

// 2. SETUP EXERCICE
$ex = new Exercise(['year' => 2026, 'interest' => 3, 'penalty_rate' => 5, 'active' => true, 'inscription_amount' => 1000, 'social_crown_amount' => 150000]);
$ex->save();
echo "✅ Exercice 2026 actif.\n";

$m1 = Member::find()->limit(1)->one(); // JEAN
$m2 = Member::find()->limit(1)->offset(1)->one(); // PAUL

// 3. SESSION 1 (Janvier) - DEPART
$s1 = new Session(['exercise_id' => $ex->id, 'date' => '2026-01-01', 'active' => false, 'administrator_id' => 1]); $s1->save();

// JEAN (Limite): 21k Epargne vs 100k Dette (Solvable au début)
$sav1 = new Saving(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 21000, 'administrator_id' => 1]); $sav1->save();
$bor1 = new Borrowing(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]); $bor1->save();

// PAUL (Riche): 50k Epargne vs 100k Dette (Large)
$sav2 = new Saving(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 50000, 'administrator_id' => 1]); $sav2->save();
$bor2 = new Borrowing(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]); $bor2->save();

echo "👤 JEAN: Epargne 21k (Solvable)\n";
echo "👤 PAUL: Epargne 50k (Solvable)\n";

// --- GENERATION DES SESSIONS ---
$months = [
    2 => 'Février', 3 => 'Mars', 4 => 'Avril (T1)', 
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet (T2)',
    8 => 'Août', 9 => 'Septembre', 10 => 'Octobre (T3)',
    11 => 'Novembre', 12 => 'Décembre', 13 => 'Janvier N+1 (T4)'
];

foreach ($months as $num => $name) {
    if ($num > 13) break; // Safety
    
    $date = date('Y-m-d', strtotime("2026-01-01 + " . ($num - 1) . " months"));
    $isActive = ($num == 13); // La dernière session est active
    
    echo "Processing Mois $num ($name)... ";
    $s = new Session(['exercise_id' => $ex->id, 'date' => $date, 'active' => $isActive, 'administrator_id' => 1]);
    $s->save();
    echo "OK.\n";
    
    // Check results for T-Sessions (4, 7, 10, 13)
    if (in_array($num, [4, 7, 10, 13])) {
        echo "   🔍 Bilan $name :\n";
        printDeduction($m1, $s, "JEAN");
        printDeduction($m2, $s, "PAUL");
    }
}

function printDeduction($member, $session, $name) {
    $deduction = Saving::find(['member_id' => $member->id, 'session_id' => $session->id])->andWhere(['<', 'amount', 0])->sum('amount');
    $status = "0";
    if ($deduction == -3000) $status = "Intérêt Standard";
    if ($deduction == -5000) $status = "PENALITE M";
    
    $totalSav = Saving::find(['member_id' => $member->id])->sum('amount');
    
    echo "      - $name: " . ($deduction ? $deduction : "RIEN") . " ($status). Reste Epargne: $totalSav\n";
}
