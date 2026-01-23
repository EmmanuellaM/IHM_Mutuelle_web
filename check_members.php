<?php
// check_members.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Member;
use app\models\Exercise;

echo "--- Vérification Membres ---\n";
$members = Member::find()->all();
echo "Total Membres en base: " . count($members) . "\n";

foreach ($members as $m) {
    // Print all attributes to be safe
    print_r($m->attributes);
}

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    echo "\n--- Vérification Calculs Exercice Model ---\n";
    // Check if distinct methods exist before calling
    try {
        echo "Exercise::totalSocialCrownAmount(): " . $ex->totalSocialCrownAmount() . "\n";
    } catch (\Exception $e) { echo "Error totalSocialCrownAmount: " . $e->getMessage() . "\n"; }
    
    try {
        echo "Exercise::numberofActiveMembers(): " . $ex->numberofActiveMembers() . "\n";
    } catch (\Exception $e) { echo "Error numberofActiveMembers: " . $e->getMessage() . "\n"; }
}
