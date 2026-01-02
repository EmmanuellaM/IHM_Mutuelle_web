<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "<h1>Création Rapide de 12 Sessions</h1>";

// Trouver l'exercice actif
$exercise = \app\models\Exercise::find()->where(['active' => true])->one();

if (!$exercise) {
    echo "<p style='color:red'>❌ Aucun exercice actif trouvé.</p>";
    exit;
}

echo "<p>✓ Exercice trouvé : <b>{$exercise->year}</b></p>";

// Compter les sessions existantes
$existingSessions = \app\models\Session::find()->where(['exercise_id' => $exercise->id])->count();
echo "<p>Sessions existantes : <b>$existingSessions</b></p>";

if ($existingSessions >= 12) {
    echo "<p style='color:orange'>⚠️ Cet exercice a déjà 12 sessions ou plus. Vous pouvez le clôturer.</p>";
    exit;
}

$sessionsToCreate = 12 - $existingSessions;
echo "<p>Sessions à créer : <b>$sessionsToCreate</b></p>";
echo "<hr>";

// Trouver un administrateur
$admin = \app\models\Administrator::find()->one();
if (!$admin) {
    echo "<p style='color:red'>❌ Aucun administrateur trouvé.</p>";
    exit;
}

// Créer les sessions
$startDate = new DateTime();
$created = 0;

for ($i = 0; $i < $sessionsToCreate; $i++) {
    $session = new \app\models\Session();
    $session->exercise_id = $exercise->id;
    $session->administrator_id = $admin->id;
    $session->date = $startDate->format('Y-m-d H:i:s');
    $session->active = false; // Sessions passées
    $session->state = 'END';
    
    if ($session->save()) {
        $created++;
        echo "<p>✓ Session " . ($existingSessions + $i + 1) . " créée (Date: " . $startDate->format('d/m/Y') . ")</p>";
        
        // Avancer d'un mois pour la prochaine session
        $startDate->modify('+1 month');
    } else {
        echo "<p style='color:red'>❌ Erreur lors de la création de la session " . ($i + 1) . "</p>";
        print_r($session->errors);
    }
}

echo "<hr>";
echo "<h3>✅ Résultat</h3>";
echo "<p><b>$created</b> sessions créées avec succès !</p>";
echo "<p>Total de sessions : <b>" . ($existingSessions + $created) . " / 12</b></p>";

if (($existingSessions + $created) >= 12) {
    echo "<p style='color:green; font-size:18px'><b>🎉 L'exercice peut maintenant être clôturé !</b></p>";
    echo "<p>Allez dans Menu > Exercices et cliquez sur 'Clôturer'</p>";
}
?>
