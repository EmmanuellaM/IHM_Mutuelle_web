<?php
// debug_real_user_state.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Borrowing;
use app\models\Saving;

echo "=== DIAGNOSTIC ÉTAT UTILISATEUR ===\n\n";

// 1. Check Exercise
$ex = Exercise::findOne(['active' => true]);
if (!$ex) die("Aucun exercice actif trouvé.\n");
echo "1. Exercice Actif: {$ex->year} (ID: {$ex->id})\n";
echo "   - Taux Interêt: {$ex->interest}%\n";
echo "   - Taux Pénalité m: {$ex->penalty_rate}%\n";

// 2. Check Sessions
$sessions = Session::find()->where(['exercise_id' => $ex->id])->orderBy('date ASC')->all();
echo "\n2. Sessions (" . count($sessions) . " trouvées):\n";
foreach ($sessions as $s) {
    echo "   - Session {$s->number()}: {$s->date} (ID: {$s->id})\n";
}

// 3. Check Borrowings
$borrowings = Borrowing::find()->where(['state' => true])->all(); // Assuming active borrowings
// Filter by exercise sessions
$exSessionIds = array_map(function($s) { return $s->id; }, $sessions);
echo "\n3. Emprunts Actifs:\n";
foreach ($borrowings as $b) {
    if (in_array($b->session_id, $exSessionIds)) {
        echo "   - Emprunt #{$b->id} (Membre {$b->member_id})\n";
        echo "     Montant: {$b->amount}\n";
        echo "     Date: " . $b->session->date . "\n";
        echo "     Intérêt Prévu (Calculé): " . ($b->amount * $b->interest / 100) . "\n";
        echo "     Elapsed Sessions: " . $b->getSessionsElapsed() . "\n";
    }
}

// 4. Check Savings (Penalties)
echo "\n4. Transactions Épargne (Pénalités Potentielles):\n";
$savings = Saving::find()->where(['session_id' => $exSessionIds])->andWhere(['<', 'amount', 0])->all();
if (count($savings) == 0) {
    echo "   AUCUNE déduction (pénalité) trouvée.\n";
} else {
    foreach ($savings as $s) {
        echo "   - Déduction: {$s->amount} (Session ID: {$s->session_id} - " . $s->session->date . ")\n";
    }
}

// 5. Calculate Total Interest Logic
echo "\n5. Simulation Calcul Intérêts Affichés:\n";
echo "   Exercise::interest() renvoie: " . $ex->interest() . "\n";
// Manually sum negative savings + borrowing interest
$totalPenalties = 0;
foreach ($savings as $s) {
    $totalPenalties += abs($s->amount);
}
echo "   Total Pénalités Réelles: $totalPenalties\n";
echo "   Total Théorique (Interest + Penalties): " . ($ex->interest() + $totalPenalties) . "\n";
