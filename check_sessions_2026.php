<?php
// check_sessions_2026.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    echo "--- Exercice 2026 ---\n";
    echo "ID: $ex->id\n";
    echo "Active: " . ($ex->active ? 'OUI' : 'NON') . "\n";
    
    $count = $ex->sessionNumber();
    echo "Nombre de sessions: $count\n";
    
    echo "Condition canBeClosed(): " . ($ex->canBeClosed() ? 'TRUE' : 'FALSE') . "\n";
    
    if ($count < 12) {
        echo "Raison: Il faut 12 sessions minimum (Manque " . (12 - $count) . ").\n";
    }
} else {
    echo "Exercice 2026 introuvable.\n";
}
