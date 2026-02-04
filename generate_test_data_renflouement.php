<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

use app\models\Exercise;
use app\models\Session;
use app\models\Member;
use app\models\Administrator;
use app\models\HelpType;
use app\models\Help;
use app\managers\FinanceManager;

echo "<h1>Génération de Données de Test pour Renflouement</h1>";

// 1. Trouver l'exercice actif
$exercise = Exercise::find()->where(['active' => true])->one();
if (!$exercise) {
    die("<p style='color:red'>❌ Erreur: Aucun exercice actif trouvé.</p>");
}
echo "<p>✓ Exercice actif trouvé: <b>{$exercise->year}</b> (ID: {$exercise->id})</p>";

$admin = Administrator::find()->one();
if (!$admin) {
    die("<p style='color:red'>❌ Erreur: Aucun administrateur trouvé.</p>");
}

// ---------------------------------------------------------
// NETTOYAGE (CLEANUP)
// ---------------------------------------------------------
echo "<h3>1. Nettoyage des données de test précédentes</h3>";
// Supprimer les aides de test créées par ce script
$deletedHelps = Help::deleteAll(['like', 'comments', 'Génération auto test renflouement%']);
echo "<p>✓ $deletedHelps aides de test supprimées.</p>";

// Supprimer les sessions END créées récemment (on suppose qu'elles sont vides de données réelles pour l'instant)
// Attention : on ne supprime que celles qui sont vides pour ne pas casser la base si l'utilisateur a fait autre chose
$sessionsToDelete = Session::find()->where(['exercise_id' => $exercise->id, 'state' => 'END'])->all();
$deletedSessions = 0;
foreach ($sessionsToDelete as $s) {
    // Vérifier si vide
    if ($s->savedAmount() == 0 && $s->borrowedAmount() == 0 && $s->refundedAmount() == 0) {
        $s->delete();
        $deletedSessions++;
    }
}
echo "<p>✓ $deletedSessions sessions vides supprimées.</p>";


// ---------------------------------------------------------
// GENERATION AIDES (BALANCED)
// ---------------------------------------------------------
echo "<h3>2. Génération des Aides (Équilibrée)</h3>";
$availableFund = FinanceManager::getAvailableSocialFund();
echo "<p>Fond Social Disponible Actuel : <b>" . number_format($availableFund, 0, ',', ' ') . " XAF</b></p>";

if ($availableFund > 0) {
    // On veut laisser un reste. Disons qu'on consomme 40% du fond.
    $targetConsumption = $availableFund * 0.4;
    $numberOfAids = 2;
    $amountPerAid = floor($targetConsumption / $numberOfAids); // Arrondi

    echo "<p>Objectif : Consommer environ 40% ($targetConsumption XAF) via $numberOfAids aides.</p>";

    $member = Member::find()->where(['active' => true])->one();
    if ($member) {
        $helpType = HelpType::find()->one();
        if (!$helpType) {
            $helpType = new HelpType();
            $helpType->title = "Aide Test";
            $helpType->amount = $amountPerAid; 
            $helpType->save();
        }

        for ($i=0; $i<$numberOfAids; $i++) {
            $help = new Help();
            $help->help_type_id = $helpType->id;
            $help->member_id = $member->id;
            $help->administrator_id = $admin->id;
            $help->comments = "Génération auto test renflouement (Balanced) " . ($i+1);
            $help->amount = $amountPerAid;
            $help->amount_from_social_fund = $amountPerAid;
            $help->unit_amount = 0;
            $help->state = false;
            
            if ($help->save()) {
                echo "<p>✓ Aide de " . number_format($amountPerAid) . " XAF créée.</p>";
            } else {
                 echo "<p style='color:red'>❌ Erreur aide.</p>";
            }
        }
        
        $newBalance = FinanceManager::getAvailableSocialFund();
        echo "<p><b>Nouveau Solde Fond Social : " . number_format($newBalance, 0, ',', ' ') . " XAF</b></p>";

    } else {
        echo "<p>Pas de membre actif pour recevoir l'aide.</p>";
    }
} else {
    echo "<p>Fond social vide ou négatif, pas de création d'aide possible.</p>";
}

// ---------------------------------------------------------
// GENERATION SESSIONS
// ---------------------------------------------------------
echo "<h3>3. Génération des Sessions (12 au total)</h3>";
$existingSessions = Session::find()->where(['exercise_id' => $exercise->id])->count();
$sessionsToCreate = 12 - $existingSessions;

if ($sessionsToCreate <= 0) {
    echo "<p>L'exercice a déjà {$existingSessions} sessions.</p>";
} else {
    // Trouver date dernière session
    $lastSession = Session::find()->where(['exercise_id' => $exercise->id])->orderBy(['date' => SORT_DESC])->one();
    $startDate = $lastSession ? new DateTime($lastSession->date) : new DateTime();
    if ($lastSession) $startDate->modify('+1 month');

    for ($i = 0; $i < $sessionsToCreate; $i++) {
        // Anti-collision naïve
        while (Session::find()->where(['date' => $startDate->format('Y-m-d H:i:s')])->exists()) {
             $startDate->modify('+1 day');
        }

        $session = new Session();
        $session->exercise_id = $exercise->id;
        $session->administrator_id = $admin->id;
        $session->date = $startDate->format('Y-m-d H:i:s');
        $session->state = 'END';
        $session->active = false;
        
        if ($session->save()) {
            echo "<p>✓ Session générée : " . $startDate->format('d/m/Y') . "</p>";
            $startDate->modify('+1 month');
        } else {
            echo "<p style='color:red'>❌ Erreur session.</p>";
        }
    }
}

echo "<hr><h3>✅ Terminé</h3>";
?>
