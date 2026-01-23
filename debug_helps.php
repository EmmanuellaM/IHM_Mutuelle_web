<?php
// debug_helps.php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Help;
use app\models\Exercise;

echo "--- Inspection de la table Help ---\n";

// Lister TOUTES les aides pour voir ce qui existe
$helps = Help::find()->all();

if (empty($helps)) {
    echo "Aucune aide trouvée dans la base de données.\n";
} else {
    echo "Il y a " . count($helps) . " aides au total.\n";
    foreach ($helps as $h) {
        $amountSocial = isset($h->amount_from_social_fund) ? $h->amount_from_social_fund : "N/A (colonne manquante?)";
        echo "ID: " . $h->id . 
             " | Date: " . $h->created_at . 
             " | Montant: " . $h->amount . 
             " | Social Fund: " . $amountSocial . "\n";
    }
}

echo "\n--- Vérification de la logique de date (Exercice 2026) ---\n";
$startDate = '2026-01-01 00:00:00';
$endDate = '2026-12-31 23:59:59';

$count2026 = Help::find()
    ->where(['between', 'created_at', $startDate, $endDate])
    ->count();
    
echo "Aides trouvées entre $startDate et $endDate : $count2026\n";

if ($count2026 > 0) {
    if ((new Help())->hasAttribute('amount_from_social_fund')) {
        $sum = Help::find()
            ->where(['between', 'created_at', $startDate, $endDate])
            ->sum('amount_from_social_fund');
        echo "Somme 'amount_from_social_fund' pour cette période : " . ($sum ?? 0) . "\n";
    } else {
        echo "ATTENTION: La colonne 'amount_from_social_fund' n'existe pas dans le modèle Help !\n";
    }
}
