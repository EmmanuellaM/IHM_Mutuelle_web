<?php
// setup_test_scenario_v3.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "=== SCENARIO 3: DEGRADATION DE LA SOLVABILITÉ ===\n\n";

// 1. CLEANUP
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
Borrowing::deleteAll();
Saving::deleteAll();
Session::deleteAll();
Exercise::deleteAll(['year' => 2026]);
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();

// 2. EXERCISE (Int=3%, Pen=5%)
$ex = new Exercise();
$ex->year = 2026;
$ex->interest = 3;
$ex->penalty_rate = 5;
$ex->active = true;
$ex->inscription_amount = 1000;
$ex->social_crown_amount = 150000;
$ex->save();

// 3. MEMBERS
$members = Member::find()->limit(2)->all();
$m1 = $members[0]; // Cas CRITIQUE
$m2 = $members[1]; // Cas SAFE

// === SETUP INITIAL (Mois 1) ===
$s1 = new Session(['exercise_id' => $ex->id, 'date' => '2026-01-01', 'active' => false, 'administrator_id' => 1]);
$s1->save();

// Membre 1: 20 000 Epargne -> 100 000 Emprunt (Limite exacte)
$sav1 = new Saving(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 20000, 'administrator_id' => 1]);
$sav1->save();
$bor1 = new Borrowing(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]);
$bor1->save();
echo "👤 Membre 1: Epargne 20k, Dette 100k. (Ratio 100% - OK au début)\n";

// Membre 2: 25 000 Epargne -> 100 000 Emprunt (Marge de 5k)
$sav2 = new Saving(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 25000, 'administrator_id' => 1]);
$sav2->save();
$bor2 = new Borrowing(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 100000, 'interest' => 3, 'state' => true]);
$bor2->save();
echo "👤 Membre 2: Epargne 25k, Dette 100k. (Ratio 125% - Large)\n";

// === TIME TRAVEL ===

// Mois 4 (Session 4) - Déduction INTERET (3000)
// On crée les sessions intermédiaires 2,3
(new Session(['exercise_id' => $ex->id, 'date' => '2026-02-01']))->save();
(new Session(['exercise_id' => $ex->id, 'date' => '2026-03-01']))->save();

echo "\n📅 Création Session 4 (Déclenche déduction intérêt)...";
$s4 = new Session(['exercise_id' => $ex->id, 'date' => '2026-04-01', 'active' => false, 'administrator_id' => 1]);
$s4->save(); 
// A ce stade:
// M1: Epargne = 20000 - 3000 = 17000. Capacité = 85000 < 100000 (Dette). -> DEVIENT INSOLVABLE.
// M2: Epargne = 25000 - 3000 = 22000. Capacité = 110000 > 100000 (Dette). -> RESTE SOLVABLE.

// Mois 7 (Session 7) - Déclenche Pénalité 'm' SI insolvable
(new Session(['exercise_id' => $ex->id, 'date' => '2026-05-01']))->save();
(new Session(['exercise_id' => $ex->id, 'date' => '2026-06-01']))->save();

echo "\n📅 Création Session 7 (Vérification solvabilité + Pénalité m)...";
$s7 = new Session(['exercise_id' => $ex->id, 'date' => '2026-07-01', 'active' => true, 'administrator_id' => 1]);
$s7->save(); // Trigger logic in Session.php

// === VERIFICATION RESULTATS ===
echo "\n\n🔍 RESULTATS:\n";

// M1
$totalPenalties1 = Saving::find()->where(['member_id' => $m1->id])->andWhere(['<', 'amount', 0])->sum('amount');
echo "Membre 1 (Devrait être pénalisé au 7e mois car 17k*5 < 100k):\n";
echo "   Total Déduit: $totalPenalties1\n";
// Detail
foreach (Saving::find()->where(['member_id' => $m1->id])->andWhere(['<', 'amount', 0])->all() as $s) {
    echo "   - Session {$s->session->number()}: {$s->amount}\n";
}

// M2
$totalPenalties2 = Saving::find()->where(['member_id' => $m2->id])->andWhere(['<', 'amount', 0])->sum('amount');
echo "\nMembre 2 (Devrait être SAUVÉ car 22k*5 > 100k):\n";
echo "   Total Déduit: $totalPenalties2\n";
// Detail
foreach (Saving::find()->where(['member_id' => $m2->id])->andWhere(['<', 'amount', 0])->all() as $s) {
    echo "   - Session {$s->session->number()}: {$s->amount}\n";
}
