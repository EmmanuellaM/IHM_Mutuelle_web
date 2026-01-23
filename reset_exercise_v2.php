<?php
// reset_exercise_v2.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Renflouement;
use app\models\Member;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    echo "--- Reset Exercice 2026 ---\n";
    
    // 1. Delete Renflouements
    $deleted = Renflouement::deleteAll(['exercise_id' => $ex->id]);
    echo "Renflouements supprimés: $deleted\n";
    
    // 2. Set Active
    $ex->active = true;
    $ex->status = 'active'; // Just in case
    if ($ex->save()) {
        echo "Exercice remis à 'active'.\n";
    } else {
        echo "Erreur sauvegarde exercice.\n";
    }
    
    // 3. Verify Active Members
    $activeCount = $ex->numberofActiveMembers();
    echo "Membres Actifs: $activeCount\n";

} else {
    echo "Exercice 2026 introuvable.\n";
}
