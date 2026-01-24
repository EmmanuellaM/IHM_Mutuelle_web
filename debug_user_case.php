<?php
// debug_user_case.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Session;
use app\models\Borrowing;
use app\models\Saving;
use app\models\Exercise;

echo "=== DIAGNOSTIC COMPLET ===\n\n";

// 1. Lister les Exercices
$expenses = Exercise::find()->all();
foreach ($expenses as $ex) {
    echo "EXERCICE {$ex->year} (ID: {$ex->id}) - Actif: {$ex->active}\n";
    echo "  Taux Pénalité: {$ex->penalty_rate}\n";
    
    // Sessions
    $sessions = Session::find()->where(['exercise_id' => $ex->id])->orderBy('date ASC')->all();
    echo "  SESSIONS :\n";
    foreach ($sessions as $s) {
        echo "    - ID: {$s->id} | Date: {$s->date} | CreatedAt: {$s->created_at} | Active: {$s->active}\n";
    }
}

echo "\nEMPRUNTS ACTIFS :\n";
$borrowings = Borrowing::find()->where(['state' => true])->all();
foreach ($borrowings as $b) {
    $sess = $b->session;
    echo "  - ID: {$b->id} | Membre: {$b->member_id} | Montant: {$b->amount} | Session Emprunt: {$sess->date} (ID: {$sess->id})\n";
    echo "    CreatedAt EmpruntSession: {$sess->created_at}\n";
    
    // Recalcul sessions elapsed "live"
    $count = Session::find()
        ->where(['exercise_id' => $sess->exercise_id])
        ->andWhere(['>', 'created_at', $sess->created_at])
        ->count();
    echo "    >> Sessions écoulées (Calcul Live): $count\n";
    
    // Check Epargnes du membre
    $savings = Saving::find()->where(['member_id' => $b->member_id])->all();
    echo "    EPARGNES DU MEMBRE :\n";
    $total = 0;
    foreach ($savings as $sav) {
        $sDate = $sav->session ? $sav->session->date : '???';
        echo "      - ID: {$sav->id} | Session: $sDate | Montant: {$sav->amount}\n";
        $total += $sav->amount;
    }
    echo "    >> TOTAL EPARGNE: $total\n";
    
    // Check Penalité spécifique
    $interestAmount = ($b->amount * $b->interest) / 100;
    echo "    >> Intérêt théorique (Pénalité attendue): -$interestAmount\n";
}
