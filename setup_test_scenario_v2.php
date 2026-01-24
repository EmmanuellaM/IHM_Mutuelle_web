<?php
// setup_test_scenario_v2.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "=== INITIALISATION SCENARIO 2 (SOLVABILITÉ) ===\n\n";

// 1. CLEANUP
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
Borrowing::deleteAll();
Saving::deleteAll();
Session::deleteAll();
Exercise::deleteAll(['year' => 2026]);
Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();

// 2. EXERCISE
$ex = new Exercise();
$ex->year = 2026;
$ex->interest = 3;      // 3% Interest
$ex->penalty_rate = 5;  // 5% Penalty 'm'
$ex->active = true;
$ex->inscription_amount = 1000;
$ex->social_crown_amount = 150000; // Not relevant for this test
if (!$ex->save()) die("Errur Exercise");
echo "✅ Exercice 2026 créé (i=3%, m=5%).\n";

// 3. SESSION 1 (Janvier)
$s1 = new Session();
$s1->exercise_id = $ex->id;
$s1->date = '2026-01-01';
$s1->active = false;
$s1->administrator_id = 1;
$s1->save();
echo "✅ Session 1 créée.\n";

// 4. MEMBERS (Get 2 members)
$members = Member::find()->limit(2)->all();
if (count($members) < 2) die("Besoin de 2 membres au moins.");
$m1 = $members[0];
$m2 = $members[1];

// === MEMBRE 1 (Paul - Pauvre en Epargne) ===
// Epargne: 20 000. Capacité: 100 000.
// Emprunt: 150 000. (Condition: 100k < 150k -> PENALITÉ)
$sav1 = new Saving(['member_id' => $m1->id, 'session_id' => $s1->id, 'amount' => 20000, 'administrator_id' => 1]);
$sav1->save();

$bor1 = new Borrowing();
$bor1->member_id = $m1->id;
$bor1->session_id = $s1->id;
$bor1->amount = 150000; // Force amount (bypass capacity check if done via script)
$bor1->interest = 3;
$bor1->state = true;
$bor1->save();
echo "👤 Membre 1 ({$m1->id}): Epargne 20k, Dette 150k. (Capacité 100k < 150k -> DANGER)\n";

// === MEMBRE 2 (Jean - Riche en Epargne) ===
// Epargne: 50 000. Capacité: 250 000.
// Emprunt: 150 000. (Condition: 250k > 150k -> SAFE)
$sav2 = new Saving(['member_id' => $m2->id, 'session_id' => $s1->id, 'amount' => 50000, 'administrator_id' => 1]);
$sav2->save();

$bor2 = new Borrowing();
$bor2->member_id = $m2->id;
$bor2->session_id = $s1->id;
$bor2->amount = 150000;
$bor2->interest = 3;
$bor2->state = true;
$bor2->save();
echo "👤 Membre 2 ({$m2->id}): Epargne 50k, Dette 150k. (Capacité 250k > 150k -> SAFE)\n";

// 5. ADVANCE TO SESSION 7 (JUILLET)
// We need to create sessions 2,3,4,5,6 to reach 7.
// Session 4 (Avril) -> Interest penalty (3%) for BOTH (as debt exists > 3 months)
// Session 7 (Juillet) -> Penalty m (5%) for M1 ONLY.

$dates = ['2026-02-01', '2026-03-01', '2026-04-01', '2026-05-01', '2026-06-01', '2026-07-01'];
foreach ($dates as $idx => $date) {
    $sessNum = $idx + 2; // Starts at Session 2
    $s = new Session();
    $s->exercise_id = $ex->id;
    $s->date = $date;
    $s->active = ($sessNum == 7); // Set Session 7 active
    $s->administrator_id = 1;
    $s->save();
    echo "📅 Session $sessNum ($date) créée.\n";
}

echo "\n🔍 VERIFICATION DES PENALITÉS 'm' (Session 7)...\n";
// Check Savings for M1 and M2 in Session 7
// Note: Session 4 interests (3%) should also be there.

$penaltiesM1 = Saving::find()->where(['member_id' => $m1->id])->andWhere(['<', 'amount', 0])->all();
$penaltiesM2 = Saving::find()->where(['member_id' => $m2->id])->andWhere(['<', 'amount', 0])->all();

echo "--- Membre 1 (Devrait avoir Intérêt (4e mois) + Pénalité m (7e mois)) ---\n";
foreach ($penaltiesM1 as $p) {
    echo "   Session {$p->session->number()}: {$p->amount}\n";
}

echo "--- Membre 2 (Devrait avoir Intérêt (4e mois) SEULEMENT) ---\n";
foreach ($penaltiesM2 as $p) {
    echo "   Session {$p->session->number()}: {$p->amount}\n";
}
