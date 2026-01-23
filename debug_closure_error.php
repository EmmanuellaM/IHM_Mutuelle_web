<?php
// debug_closure_error.php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Renflouement;
use app\models\Exercise;
use app\models\Member;

echo "\n--- Simulation Clôture Exercice 2026 ---\n";
$ex2026 = Exercise::findOne(['year' => 2026]);
if (!$ex2026) {
    die("Exercice 2026 introuvable.\n");
}

$nextEx = Exercise::findOne(['year' => 2027]);
if (!$nextEx) {
    die("Exercice 2027 introuvable.\n");
}

echo "Exercice 2026 Active: " . $ex2026->active . "\n";
echo "Exercice 2027 Active: " . $nextEx->active . "\n";

// Test Calculation
$amount = $ex2026->calculateRenflouementPerMember();
echo "Montant calculé par le modèle: $amount\n";

if ($amount > 0) {
    echo "Tentative de création d'un Renflouement factice pour voir les erreurs...\n";
    
    $member = Member::find()->where(['active' => true])->one();
    if ($member) {
        $renflouement = new Renflouement();
        $renflouement->member_id = $member->id;
        $renflouement->exercise_id = $ex2026->id;
        $renflouement->next_exercise_id = $nextEx->id;
        $renflouement->amount = $amount;
        $renflouement->status = Renflouement::STATUS_PENDING;
        $renflouement->start_session_number = 1;
        
        if (!$renflouement->validate()) {
            echo "ERREUR DE VALIDATION:\n";
            print_r($renflouement->getErrors());
        } else {
            echo "Validation Renflouement OK.\n";
            
            // Try saving to see if database error occurs (rollback immediately)
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                if ($renflouement->save()) {
                     echo "Sauvegarde Test OK (On rollback).\n";
                } else {
                     echo "Sauvegarde échouée.\n";
                     print_r($renflouement->getErrors());
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                echo "Exception lors de la sauvegarde: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "Aucun membre actif pour le test.\n";
    }
} else {
    echo "Montant calculé est 0 or négatif, donc pas de création.\n";
}
