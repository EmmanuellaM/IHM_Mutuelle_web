<?php
// check_status_2026.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Renflouement;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    echo "--- Etat Exercice 2026 ---\n";
    echo "ID: $ex->id\n";
    echo "Active: " . ($ex->active ? 'OUI' : 'NON') . "\n";
    echo "Status: $ex->status\n";
    
    $renflouements = Renflouement::find()->where(['exercise_id' => $ex->id])->all();
    echo "Nombre de renflouements liés: " . count($renflouements) . "\n";
    foreach ($renflouements as $r) {
        echo "- Member " . $r->member_id . ": " . $r->amount . " (Status: " . $r->status . ")\n";
    }
} else {
    echo "Exercice 2026 introuvable.\n";
}
