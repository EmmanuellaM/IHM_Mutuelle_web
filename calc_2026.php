<?php
// calc_2026.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    ob_start();
    echo "--- Calcul Renflouement 2026 ---\n";
    
    $members = $ex->numberofActiveMembers();
    $crown = $ex->social_crown_amount;
    $max = $members * $crown;
    
    $agape = $ex->totalAgapeAmount();
    $helps = $ex->getTotalHelpsFromSocialFund();
    $expenses = $agape + $helps;
    
    $diff = $max - $expenses;
    
    echo "Membres: $members\n";
    echo "Montant Fond Social: $crown\n";
    echo "Max Théorique: $max\n";
    echo "Dépenses (Agape+Aides): $expenses\n";
    echo "Différence: $diff\n";
    
    $final = 0;
    if ($members > 0) {
        $perMember = ceil($diff / $members);
        $final = $perMember > 0 ? $perMember : 0;
        echo "Renflouement par membre: " . $final . "\n";
    } else {
        echo "0 membre.\n";
    }
    
    $out = ob_get_clean();
    echo $out;
    file_put_contents('result.txt', $out);
} else {
    echo "Exercice 2026 introuvable.\n";
}
