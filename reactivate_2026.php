<?php
// reactivate_2026.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    if (!$ex->active) {
        $ex->active = true;
        // Optionally reset status text if used
        if ($ex->save()) {
            echo "Exercice 2026 réactivé avec succès.\n";
        } else {
            echo "Erreur lors de la réactivation:\n";
            print_r($ex->getErrors());
        }
    } else {
        echo "L'exercice 2026 est déjà actif.\n";
    }
} else {
    echo "Exercice 2026 introuvable.\n";
}
