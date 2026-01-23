<?php
// sim_revert.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    ob_start();
    echo "--- Simulation Retour Formule (Dépenses / Membres) ---\n";
    
    $activeMembers = $ex->numberofActiveMembers();
    $agape = $ex->totalAgapeAmount();
    $helps = $ex->getTotalHelpsFromSocialFund();
    $expenses = $agape + $helps;
    
    echo "Membres Actifs: $activeMembers\n";
    echo "Total Dépenses: $expenses\n";
    
    if ($activeMembers > 0) {
        $amount = ceil($expenses / $activeMembers);
        echo "Renflouement par membre: $amount\n";
    }

    $out = ob_get_clean();
    file_put_contents('revert_result.txt', $out);
    echo $out;
}
