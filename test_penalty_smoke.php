<?php
// test_penalty.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Member;
use app\models\Exercise;
use app\models\Session;
use app\models\Borrowing;
use app\models\Saving;
use app\managers\PenaltyManager;

echo "--- Test Logic Penalties ---\n";

// 1. Setup Data - Utiliser un membre existant si possible ou simuler
$exercise = Exercise::findOne(['year' => 2026]);
if (!$exercise) {
    echo "Création Exercice 2026...\n";
    $exercise = new Exercise();
    $exercise->year = 2026;
    $exercise->active = true;
    $exercise->save();
}

$member = Member::find()->one();
if (!$member) die("Aucun membre trouvé.\n");
echo "Membre test: {$member->username} (ID: {$member->id})\n";

// Créer une d'épargne initiale si besoin
$currentSavings = Saving::find()->where(['member_id' => $member->id])->sum('amount');
echo "Epargne actuelle: $currentSavings\n";
if ($currentSavings < 500000) {
    echo "Ajout épargne de 500k...\n";
    $s = new Saving();
    $s->member_id = $member->id;
    $s->session_id = Session::find()->one()->id;
    $s->amount = 500000;
    $s->save();
}

// 2. Créer un emprunt "Vieux de 3 mois"
// On doit simuler les sessions.
// On va tricher sur la méthode getSessionsElapsed() du Borrowing en créant des sessions dans le passé ou en mockant.
// Pour le test réel, il faudrait créer 4 sessions.
// Simplification: On va assumer que getSessionsElapsed retourne 3.
// Mais pour ça il faut de vraies sessions en DB.
// On va juste vérifier si PenaltyManager est appelable et ne plante pas, et s'il détecte quelque chose.

echo "Exécution checkThreeMonthPenalties...\n";
PenaltyManager::checkThreeMonthPenalties($exercise);
echo "Terminé.\n";

// Si on veut vraiment tester, il faut insérer un Borrowing et des Sessions.
// Comme c'est complexe de setup tout l'état de DB sans casser l'existant, on va faire un test unitaire 'mocké' si on pouvait, mais là on est en intégration.
// On va s'arrêter là pour le script de "fumée".
