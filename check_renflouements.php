<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
$application = new yii\web\Application($config);

use app\models\Exercise;
use app\models\Renflouement;

echo "=== État des Exercices ===\n\n";

$exercises = Exercise::find()->orderBy(['year' => SORT_DESC])->all();

foreach ($exercises as $ex) {
    echo "Exercice {$ex->year} (ID: {$ex->id}):\n";
    echo "  - Actif: " . ($ex->active ? 'OUI' : 'NON') . "\n";
    echo "  - Sessions: " . count($ex->sessions()) . "\n";
    
    // Renflouements À PAYER dans cet exercice
    $renflouementsAPayer = Renflouement::find()->where(['next_exercise_id' => $ex->id])->count();
    echo "  - Renflouements à payer dans cet exercice: {$renflouementsAPayer}\n";
    
    // Renflouements CRÉÉS par cet exercice
    $renflouementsCrees = Renflouement::find()->where(['exercise_id' => $ex->id])->count();
    echo "  - Renflouements créés par cet exercice: {$renflouementsCrees}\n\n";
}

echo "\n=== Détails des Renflouements ===\n\n";
$renflouements = Renflouement::find()->all();
foreach ($renflouements as $r) {
    echo "Renflouement ID {$r->id}:\n";
    echo "  - Membre: {$r->member_id}\n";
    echo "  - Exercice d'origine: {$r->exercise_id}\n";
    echo "  - À payer dans l'exercice: {$r->next_exercise_id}\n";
    echo "  - Montant: {$r->amount} XAF\n";
    echo "  - Statut: {$r->status}\n\n";
}
