<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "<h1>Vérification de la logique de Renflouement</h1>";

// 1. Exercice Actif
$exercise = \app\models\Exercise::find()->where(['active' => true])->one();
if (!$exercise) {
    echo "<p style='color:red'>❌ Aucun exercice actif trouvé.</p>";
} else {
    echo "<h3>1. État de l'exercice actuel</h3>";
    echo "<ul>";
    echo "<li>Année : <b>{$exercise->year}</b></li>";
    echo "<li>Nombre de sessions : <b>" . count($exercise->sessions()) . " / 12</b></li>";
    echo "</ul>";

    // 2. Renflouements en attente de cet exercice (provenant de l'exercice précédent)
    $renflouements = \app\models\Renflouement::find()
        ->where(['next_exercise_id' => $exercise->id])
        ->all();

    if ($renflouements) {
        echo "<h3>2. Renflouements en cours pour cet exercice</h3>";
        $elapsed = $renflouements[0]->getSessionsElapsed();
        echo "<p>Sessions écoulées : <b>$elapsed / 3</b></p>";
        
        echo "<table border='1' cellpadding='5' style='border-collapse:collapse'>";
        echo "<tr><th>Membre</th><th>Montant</th><th>Payé</th><th>Statut Renfl.</th><th>Statut Membre</th></tr>";
        foreach ($renflouements as $r) {
            $m = $r->member;
            $color = ($m->active) ? "green" : "red";
            $status_color = ($r->status == \app\models\Renflouement::STATUS_PAID) ? "green" : "orange";
            if ($r->status == \app\models\Renflouement::STATUS_LATE) $status_color = "red";

            echo "<tr>";
            echo "<td>" . $m->user()->name . "</td>";
            echo "<td>" . $r->amount . "</td>";
            echo "<td>" . $r->paid_amount . "</td>";
            echo "<td style='color:$status_color'>" . $r->status . "</td>";
            echo "<td style='color:$color'>" . ($m->active ? "ACTIF" : "INACTIF") . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        if ($elapsed >= 3) {
            echo "<p style='color:red'><b>⚠️ ATTENTION :</b> La prochaine session désactivera tous les membres 'ACTIF' listés ci-dessus qui n'ont pas payé totallement.</p>";
        } else {
            echo "<p style='color:blue'><b>ℹ️ INFO :</b> Il reste " . (3 - $elapsed) . " session(s) avant la désactivation automatique.</p>";
        }
    } else {
        echo "<p>Aucun renflouement rattaché à cet exercice pour le moment.</p>";
    }
}

echo "<hr>";
echo "<h3>📚 Guide de Test Manuel</h3>";
echo "<ol>";
echo "<li><b>Phase 1 (Génération) :</b> Allez dans l'Admin > Exercices. Si vous avez 12 sessions, cliquez sur 'Clôturer'.</li>";
echo "<li><b>Phase 2 (Sessions) :</b> Allez dans l'Admin > Accueil et créez une session. Revenez ici pour voir le compteur augmenter.</li>";
echo "<li><b>Phase 3 (Désactivation) :</b> À la création de la <b>4ème session</b>, vérifiez que les membres impayés sont passés en 'INACTIF' ici ou dans la liste des membres.</li>";
echo "</ol>";
?>
