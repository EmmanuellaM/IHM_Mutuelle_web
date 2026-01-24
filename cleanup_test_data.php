<?php
// cleanup_test_data.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Exercise;
use app\models\Session;
use app\models\Borrowing;
use app\models\Saving;

echo "🧹 Nettoyage des données de test (Exercice 2026)...\n";

$ex = Exercise::findOne(['year' => 2026]);
if ($ex) {
    // Delete related data
    $sessions = Session::find()->where(['exercise_id' => $ex->id])->all();
    foreach ($sessions as $s) {
        Saving::deleteAll(['session_id' => $s->id]);
        Borrowing::deleteAll(['session_id' => $s->id]);
        $s->delete();
    }
    $ex->delete();
    echo "✅ Exercice 2026 et toutes ses données ont été supprimés.\n";
} else {
    echo "ℹ️ Aucun exercice 2026 trouvé.\n";
}

echo "Base de données propre.\n";
