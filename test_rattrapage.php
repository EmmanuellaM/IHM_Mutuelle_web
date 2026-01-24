<?php
// test_rattrapage.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php'; // Using console config for script
$application = new yii\console\Application($config);

use app\models\Member;
use app\models\Exercise;
use app\models\Session;
use app\models\Borrowing;
use app\models\Saving;

echo "--- DEBUT TEST RATTRAPAGE ---\n";

$transaction = Yii::$app->db->beginTransaction();
try {
    // 1. CLEANUP
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
    $year = 2026;
    $ex = Exercise::findOne(['year' => $year]);
    if ($ex) {
        $exSessions = Session::find()->where(['exercise_id' => $ex->id])->all();
        foreach($exSessions as $s) {
            // Also clean borrowing_remboursement if exists, but FK check disable helps
            Saving::deleteAll(['session_id' => $s->id]);
            Borrowing::deleteAll(['session_id' => $s->id]);
            $s->delete();
        }
        $ex->delete();
    }
    Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();

    // 2. SETUP
    $exercise = new Exercise();
    $exercise->year = $year;
    $exercise->interest = 5;
    $exercise->penalty_rate = 10;
    $exercise->active = true;
    $exercise->inscription_amount = 0;
    $exercise->social_crown_amount = 0;
    $exercise->administrator_id = 1;
    
    if (!$exercise->save()) {
        throw new Exception("Erreur création exercice: " . json_encode($exercise->errors));
    }
    echo "Exercice 2026 créé (ID: {$exercise->id}).\n";

    $member = Member::findOne(['inscription' => 1]);
    if (!$member) {
        $member = new Member(['user_id'=>1, 'inscription'=>1]); // Simplified
        $member->save(false);
    }
    echo "Membre ID: {$member->id}\n";

    // 3. SCENARIO
    
    // Session 1 + Emprunt
    $s1 = new Session(['exercise_id'=>$exercise->id, 'date'=>"$year-01-01", 'active'=>false, 'administrator_id'=>1]);
    $s1->save();
    sleep(1); // Ensure timestamp diff

    $borrowing = new Borrowing();
    $borrowing->member_id = $member->id;
    $borrowing->session_id = $s1->id;
    $borrowing->amount = 100000;
    $borrowing->interest = 5;
    $borrowing->state = true;
    $borrowing->administrator_id = 1;
    $borrowing->save();
    echo "Emprunt créé à la session 1.\n";
    
    // Session 2, 3
    $s2 = new Session(['exercise_id'=>$exercise->id, 'date'=>"$year-02-01", 'active'=>false, 'administrator_id'=>1]); $s2->save(); sleep(1);
    $s3 = new Session(['exercise_id'=>$exercise->id, 'date'=>"$year-03-01", 'active'=>false, 'administrator_id'=>1]); $s3->save(); sleep(1);

    // Session 4 (Should trigger penalty)
    $s4 = new Session(['exercise_id'=>$exercise->id, 'date'=>"$year-04-01", 'active'=>true, 'administrator_id'=>1]); 
    $s4->save();
    echo "Session 4 créée.\n";

    $penalty = Saving::find()
        ->where(['member_id'=>$member->id, 'session_id'=>$s4->id])
        ->andWhere(['<', 'amount', 0])
        ->one();
    
    if (!$penalty) {
        $savings = Saving::find()->where(['member_id'=>$member->id])->all();
        echo "DEBUG: Savings found for member {$member->id}:\n";
        foreach($savings as $s) {
            echo "- ID: {$s->id}, Session: {$s->session_id}, Amount: {$s->amount}, Admin: {$s->administrator_id}\n";
        }
        echo "DEBUG: Looking for SessionID: {$s4->id}\n";
        throw new Exception("ERREUR INITIALE: La pénalité n'a pas été créée à l'insertion de S4 !");
    }
    echo "Pénalité initiale trouvée (ID: {$penalty->id}).\n";

    // 4. SIMULATE BUG / DELETION
    $penalty->delete();
    echo "Pénalité supprimée manuellement.\n";

    $check = Saving::findOne($penalty->id);
    if ($check) throw new Exception("Suppression a échoué.");

    // 5. TEST RATTRAPAGE (Update S4)
    echo "Tentative de rattrapage via S4->save()...\n";
    $s4->active = false; // Change something just in case, though save() runs anyway
    $s4->save();

    $penalty2 = Saving::find()
        ->where(['member_id'=>$member->id]) // ID might be new, session might be S4
        ->andWhere(['<', 'amount', 0])
        ->one();

    if ($penalty2) {
        echo "SUCCES: La pénalité a été recréée (ID: {$penalty2->id}) !\n";
    } else {
        echo "ECHEC: La pénalité n'a pas été recréée après update.\n";
        exit(1); 
    }

} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    $transaction->rollBack();
    exit(1);
}

// Rollback cleanup
$transaction->rollBack();
echo "Test terminé (Rollback effectué).\n";
