<?php
// trace_calc.php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;

echo "Start Trace.\n";
$ex = Exercise::findOne(['year' => 2026]);
if (!$ex) die("Ex 2026 not found.\n");

echo "Ex found. Active members: " . $ex->numberofActiveMembers() . "\n";

echo "Calling totalAgapeAmount...\n";
try {
    $agape = $ex->totalAgapeAmount();
    echo "Agape: $agape\n";
} catch (\Exception $e) {
    echo "Error in Agape: " . $e->getMessage() . "\n";
}

echo "Calling getTotalHelpsFromSocialFund...\n";
try {
    $helps = $ex->getTotalHelpsFromSocialFund();
    echo "Helps: $helps\n";
} catch (\Exception $e) {
    echo "Error in Helps: " . $e->getMessage() . "\n";
}

echo "Calling calculateRenflouementPerMember...\n";
try {
    $total = $ex->calculateRenflouementPerMember();
    echo "Total: $total\n";
} catch (\Exception $e) {
    echo "Error in Total: " . $e->getMessage() . "\n";
}

echo "End Trace.\n";
