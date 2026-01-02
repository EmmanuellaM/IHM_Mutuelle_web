<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "=== Member Status Analysis ===\n";
$members = \app\models\Member::find()->all();
$activeCount = 0;

foreach ($members as $member) {
    if ($member->active) {
        $activeCount++;
    }
    echo "ID: " . $member->id . 
         " | Name: " . $member->user->name . " " . $member->user->first_name . 
         " | Active: " . ($member->active ? 'YES' : 'NO') . "\n";
}

echo "\nTotal Active Members found: " . $activeCount . "\n";
echo "Total Expenses (Help + Agape): " . \app\models\Exercise::findOne(['status' => 'closed']) ? \app\models\Exercise::findOne(['status' => 'closed'])->calculateRenflouementPerMember() * $activeCount : "No closed exercise" . "\n"; 
// Note: calculateRenflouementPerMember returns (Total / Count). So Total = Result * Count.
