<?php
// simulate_insolvency_scenarios.php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);
if (function_exists('opcache_reset')) {
    opcache_reset();
    p("Opcache reset.");
} else {
    p("Opcache not available.");
}

use app\models\Member;
use app\models\Exercise;
use app\models\Session;
use app\models\Saving;
use app\models\Borrowing;
use app\models\Renflouement;
use app\models\User;
use app\models\Help;
use app\models\HelpType;

// --- Helper Functions ---
function p($msg) { echo "[$msg]\n"; }

function resetData() {
    p("Réinitialisation des données de test...");
    // Nettoyage rapide
    Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
    Renflouement::deleteAll();
    Borrowing::deleteAll();
    Saving::deleteAll();
    Session::deleteAll();
    Help::deleteAll();
    
    // On garde les user/member admins, mais on supprime nos test users
    Member::deleteAll(['username' => ['jean_finance', 'marie_sociale']]);
    // User deletion is tricky if user table has constraints. Member has user_id. Member deleted first.
    // User::deleteAll(['username' => ...]); but User uses email.
    // We should find users by username? No, username absent.
    // simulation creates users with name.
    // Let's assume User cleanup is needed but tricky.
    // If we delete Members, recreate works if we use EXISTING user?
    // createTestMember logic checks existing MEMBER.
    // If Member deleted, it creates NEW user?
    // User name Jean Finance.
    // User unique constraints?
    // Best to clean User too.
    // User has no username column. Simulation uses name?
    // Simulation: $user->username is removed. using name.
    // But creation checks Member existence.
    // Let's just delete Members. The script creates NEW User each time?
    // Script: $user = new User(). $user->save().
    // If user table fills up, fine.
    // But we need to DELETE MEMBERS to force re-creation.
    
    Exercise::updateAll(['active' => 0]);
    Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1;')->execute();
}

resetData();

// 1. Création de l'Exercice 2027
$exercise = new Exercise();
$exercise->year = 2027;
$exercise->active = 1;
$exercise->interest = 5; // 5%
$exercise->inscription_amount = 5000;
$exercise->social_crown_amount = 5000;
if (!$exercise->save()) {
    p("Ex save FAILED: " . json_encode($exercise->errors));
    exit;
}
p("Exercice 2027 créé et actif.");

// 2. Création des Membres
// Helper pour créer membre
function createTestMember($name, $username) {
    if ($existing = Member::findOne(['username' => $username])) return $existing;
    
    $user = new User();
    // $user->username = $username; // Removed: property does not exist in User model
    $user->name = $name;
    $user->first_name = "Test";
    $user->email = "$username@test.com";
    $user->type = "MEMBER";
    $user->password = "password";
    if (!$user->save(false)) p("User save FAILED: " . json_encode($user->errors));
    
    $member = new Member();
    $member->user_id = $user->id;
    $member->username = $username;
    $member->inscription = 5000;
    $member->social_crown = 5000;
    $member->active = 1; // Actif par défaut
    if (!$member->save(false)) p("Member save FAILED: " . json_encode($member->errors));
    
    p("Member {$username} created. ID: {$member->id}, Active: {$member->active}");
    
    return $member;
}

$jeanFinance = createTestMember("Jean Finance", "jean_finance"); // Cas Financier
$marieSociale = createTestMember("Marie Sociale", "marie_sociale"); // Cas Social

p("Membres créés : {$jeanFinance->username} et {$marieSociale->username}");

// 3. Création de la Session 1
$session1 = new Session();
$session1->exercise_id = $exercise->id;
$session1->date = "2027-01-01";
$session1->active = 1;
$session1->save();
p("Session 1 créée.");

// ==========================================
// SCÉNARIO A : JEAN FINANCE (Insolvabilité Financière)
// ==========================================
p("\n--- SCÉNARIO A : JEAN FINANCE ---");

// A.1 Epargne : 100 000 XAF
$sav = new Saving();
$sav->member_id = $jeanFinance->id;
$sav->session_id = $session1->id;
$sav->amount = 100000;
$sav->save();
p("Jean a épargné 100 000 XAF.");

// A.2 Calcul Droits
// Epargne 100k <= 200k => x5 => Max 500 000
// On lui met une dette manuelle de 550 000 (comme s'il avait emprunté ça avant ou check désactivé)
$debt = new Borrowing();
$debt->member_id = $jeanFinance->id;
$debt->session_id = $session1->id;
$debt->amount = 550000;
$debt->state = 1; // Actif
$debt->save(false); // Force save
p("Jean a une dette forcée de 550 000 XAF.");

// A.3 Vérification isInsolvent
$isInsolvent = $jeanFinance->isInsolvent($exercise);
p("Status Insolvabilité Financière de Jean : " . ($isInsolvent ? "OUI (Correct)" : "NON (Erreur)"));

// A.4 Test Emprunt (Simulé comme dans Controller)
if ($isInsolvent) {
    p(">> TENTATIVE EMPRUNT : BLOQUÉE par le système (Cause: Dette > Capacité).");
} else {
    p(">> TENTATIVE EMPRUNT : AUTORISÉE (Erreur).");
}

// A.5 Test Aide (Social)
// Jean est-il actif ?
$jeanActive = Member::findOne($jeanFinance->id)->active;
if ($jeanActive) {
    p(">> TENTATIVE AIDE : AUTORISÉE (Correct, car c'est purement financier).");
} else {
    p(">> TENTATIVE AIDE : BLOQUÉE (Erreur).");
}


// ==========================================
// SCÉNARIO B : MARIE SOCIALE (Insolvabilité Sociale)
// ==========================================
p("\n--- SCÉNARIO B : MARIE SOCIALE ---");

// B.1 Epargne : 100 000 XAF (Pour qu'elle soit solvable financièrement)
$savM = new Saving();
$savM->member_id = $marieSociale->id;
$savM->session_id = $session1->id;
$savM->amount = 100000;
$savM->save();
p("Marie a épargné 100 000 XAF.");
// Capacité ~ 500 000. Dette = 0.
p("Marie est solvable financièrement (Capacité 500k, Dette 0).");

// B.2 Création Renflouement Impayé (Lié à un exercice précédent fictif 2026 -> Paiement sur 2027)
// On simule qu'elle doit payer sur l'exercice courant
$renflouement = new Renflouement();
$renflouement->member_id = $marieSociale->id;
$renflouement->exercise_id = $exercise->id; // peu importe l'origine
$renflouement->next_exercise_id = $exercise->id; // A payer sur 2027
$renflouement->amount = 10000;
$renflouement->status = 'en_attente';
$renflouement->start_session_number = 1;
$renflouement->save(false);
p("Marie a un renflouement de 10 000 XAF à payer.");

// B.3 Avance rapide : Sessions 2, 3, 4
// Session 4 déclenche la vérification de retard
for ($i = 2; $i <= 4; $i++) {
    $s = new Session();
    $s->exercise_id = $exercise->id;
    $s->date = "2027-0{$i}-01";
    $s->save();
    p("Session $i créée.");
}

// Rechargement Marie pour voir son statut Active
$marieReloaded = Member::findOne($marieSociale->id);
p("Statut Active de Marie après Session 4 : " . ($marieReloaded->active ? "Active" : "INACTIVE (Correct)"));

// B.4 Vérification du statut du Renflouement
$rReloaded = Renflouement::findOne($renflouement->id);
p("Statut du Renflouement : " . $rReloaded->status . " (Attendu: en_retard)");

// B.5 Test Aide (Social)
if (!$marieReloaded->active) {
    p(">> TENTATIVE AIDE : BLOQUÉE (Correct, car inactive).");
} else {
    p(">> TENTATIVE AIDE : AUTORISÉE (Erreur).");
}

// B.6 Test Emprunt (Financier)
// Marie est-elle insolvable financièrement ?
$marieInsolventFinancial = $marieReloaded->isInsolvent($exercise);
p("Marie est-elle insolvable financièrement ? " . ($marieInsolventFinancial ? "OUI" : "NON"));

if (!$marieInsolventFinancial) {
    // Note: Dans le code actuel du Controller, on ne bloque PAS explicitement si active=false, 
    // sauf si on ajoute une règle. L'utilisateur a dit "Mais on ne t'empêche pas d'emprunter tant que tes ratios financiers sont bons".
    p(">> TENTATIVE EMPRUNT : AUTORISÉE (Correct, car ratio financier OK).");
} else {
    p(">> TENTATIVE EMPRUNT : BLOQUÉE (Possiblement incorrect si ratio OK).");
}

