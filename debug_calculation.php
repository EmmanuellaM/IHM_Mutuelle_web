<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "=== Renflouement Calculation Debug ===\n";

$ex = \app\models\Exercise::findOne(['status' => 'closed']);
if (!$ex) {
    echo "No closed exercise found. Checking for active 2025...\n";
    $ex = \app\models\Exercise::findOne(['year' => 2025]);
}

if (!$ex) {
    echo "No exercise found.\n";
    exit;
}

echo "Exercise Year: " . $ex->year . "\n";
echo "Active: " . $ex->active . "\n";
echo "Status: " . $ex->status . "\n\n";

// 1. Members
$count = $ex->numberofActiveMembers();
echo "numberofActiveMembers() returns: " . $count . "\n";
$members = \app\models\Member::find()->where(['active' => 1])->all();
echo "Actual Active Members List:\n";
foreach($members as $m) echo " - " . $m->user->name . " (ID: " . $m->id . ")\n";

// 2. Helps
echo "\ngetTotalHelpsFromSocialFund() returns: " . number_format($ex->getTotalHelpsFromSocialFund()) . " XAF\n";
echo "Detailed Help Records (filtered by date):\n";
$startDate = $ex->year . '-01-01 00:00:00';
$endDate = $ex->year . '-12-31 23:59:59';
$helps = \app\models\Help::find()->where(['between', 'created_at', $startDate, $endDate])->all();

$sum = 0;
foreach($helps as $h) {
    echo " - Help ID: " . $h->id . " | Amount: " . $h->amount_from_social_fund . " | Date: " . $h->created_at . "\n";
    $sum += $h->amount_from_social_fund;
}
echo "Manual Sum of Helps: " . $sum . " XAF\n";

// 3. Final Calc
echo "\nFinal Calculation:\n";
echo "Total Expenses / Active Members = " . $sum . " / " . $count . " = " . ($count > 0 ? ceil($sum / $count) : 0) . "\n";
