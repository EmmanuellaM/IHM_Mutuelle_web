<?php
// get_exercise_params.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Borrowing;

$ex = Exercise::findOne(['active' => true]);
if (!$ex) die("Pas d'exercice actif.");

echo "Exercice ID: {$ex->id}\n";
echo "Taux Intérêt Emprunt (Config): {$ex->interest}%\n";
echo "Taux Pénalité m (Config): {$ex->penalty_rate}%\n";

echo "--- Emprunts Actifs ---\n";
$borrowings = \app\managers\FinanceManager::exerciseActiveBorrowings();
foreach ($borrowings as $b) {
    echo "Emprunt #{$b->id}: Montant {$b->amount} | Taux appliqué: {$b->interest}%\n";
}
