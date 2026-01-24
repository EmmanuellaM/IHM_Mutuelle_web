<?php
// verify_results.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Saving;
use app\models\Member;

$members = Member::find()->limit(2)->all();
$m1 = $members[0]; // Paul (Pauvre)
$m2 = $members[1]; // Jean (Riche)

echo "--- Membre 1 (Paul) ---\n";
$pen1 = Saving::find()->where(['member_id' => $m1->id])->andWhere(['<', 'amount', 0])->all();
foreach ($pen1 as $p) echo "Session {$p->session->number()}: {$p->amount}\n";

echo "\n--- Membre 2 (Jean) ---\n";
$pen2 = Saving::find()->where(['member_id' => $m2->id])->andWhere(['<', 'amount', 0])->all();
foreach ($pen2 as $p) echo "Session {$p->session->number()}: {$p->amount}\n";
