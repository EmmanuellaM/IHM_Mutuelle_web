<?php
// debug_penalty_state.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Borrowing;
use app\models\Session;
use app\models\Saving;

echo "--- DIAGNOSTIC ETAT EMPRUNTS & PENALITES ---\n\n";

$borrowings = Borrowing::find()->where(['state' => true])->all();

foreach ($borrowings as $borrowing) {
    $member = $borrowing->member();
    echo "EMPRUNT #{$borrowing->id} (Membre: {$member->id} - {$member->user()->name})\n";
    echo "  Montant: {$borrowing->amount}\n";
    
    $sess = $borrowing->session;
    echo "  Session Emprunt: ID {$sess->id} Date: {$sess->date} (Créé le: {$sess->created_at})\n";
    echo "  Timestamp Emprunt Session: " . $sess->created_at . "\n";
    
    // Calcul sessions écoulées
    $exercise = $sess->exercise();
    $countQuery = Session::find()
            ->where(['exercise_id' => $exercise->id])
            ->andWhere(['>', 'created_at', $sess->created_at]);
            
    $count = $countQuery->count();
    $sessionsFound = $countQuery->all();
    
    echo "  Sessions Ecoulees (Count): $count\n";
    echo "  Détail Sessions > Date Emprunt:\n";
    foreach ($sessionsFound as $s) {
        echo "    - Session ID {$s->id} Date: {$s->date()} (Créé le: {$s->created_at})\n";
    }
    
    echo "  Status Attendu : ";
    if ($count == 3) echo "[PENALITE 3 MOIS DUE]\n";
    elseif ($count > 3) echo "[PENALITE 3 MOIS PASSEE (Aurait dû être faite)]\n";
    else echo "[RAS - Trop tôt]\n";
    
    // Vérification si Epargne Négative existe (trace de pénalité)
    // On cherche une épargne négative correspondant aux intérêts
    $interestAmount = ($borrowing->amount * $borrowing->interest) / 100;
    
    // On cherche une épargne d'environ -interet
    $penalties = Saving::find()
        ->where(['member_id' => $borrowing->member_id])
        ->andWhere(['<', 'amount', 0])
        ->all();
        
    if (count($penalties) > 0) {
        echo "  [INFO] Epargnes négatives trouvées (Preuve de pénalité ?) :\n";
        foreach ($penalties as $p) {
            echo "    - ID {$p->id} Session {$p->session->date()}: {$p->amount} XAF\n";
        }
    } else {
        echo "  [INFO] Aucune épargne négative trouvée pour ce membre.\n";
    }
    
    echo "---------------------------------------------------\n";
}
