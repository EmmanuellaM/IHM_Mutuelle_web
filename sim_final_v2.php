<?php
// sim_final_v2.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Member;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    ob_start();
    echo "--- Simulation Finale V2 ---\n";
    
    $activeMembers = $ex->numberofActiveMembers();
    
    // CORRECTED LOGIC
    $payingMembersCount = Member::find()
            ->where(['>=', 'social_crown', $ex->social_crown_amount])
            ->count();
            
    $crown = $ex->social_crown_amount;
    $realSocialFund = $payingMembersCount * $crown;
    
    $agape = $ex->totalAgapeAmount();
    $helps = $ex->getTotalHelpsFromSocialFund();
    $expenses = $agape + $helps;
    
    $diff = $realSocialFund - $expenses;
    
    echo "Membres Actifs: $activeMembers\n";
    echo "Membres Ayant Payé (>= $crown): $payingMembersCount\n";
    echo "Montant Cotisation: $crown\n";
    echo "Fonds Social Total (Payé * Cotisation): $realSocialFund\n";
    
    echo "Dépenses (Agape: $agape + Aides: $helps): $expenses\n";
    echo "Reste (Fonds - Dépenses): $diff\n";
    
    if ($activeMembers > 0) {
        $perMember = ceil($diff / $activeMembers);
        $final = $perMember > 0 ? $perMember : 0;
        echo "Renflouement par membre: " . $final . "\n";
    } else {
        echo "0 membre actif.\n";
    }
    
    $out = ob_get_clean();
    file_put_contents('final_result_v2.txt', $out);
    echo $out;
} else {
    echo "Exercice 2026 introuvable.\n";
}
