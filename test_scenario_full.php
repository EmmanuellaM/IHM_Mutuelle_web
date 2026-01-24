<?php
/**
 * Script de test complet pour la validation de la logique d'emprunt et pénalités.
 * Ce script crée un environnement isolé (Exercice de test, Membre de test) pour ne pas polluer les données réelles.
 * Il simule ensuite le passage du temps pour vérifier les pénalités.
 */

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

echo "\n======================================================\n";
echo "    TEST SCENARIO COMPLET : EMPRUNTS & PENALITES\n";
echo "======================================================\n\n";

$transaction = Yii::$app->db->beginTransaction();
try {
    // ---------------------------------------------------------
    // 1. SETUP - Création de l'environnement de test
    // ---------------------------------------------------------
    echo "[1/6] Initialisation de l'environnement de test...\n";
    
    // Créer un exercice de test (Année 2099 pour être sûr)
    $exercise = new Exercise();
    $exercise->year = 2099;
    $exercise->active = true;
    $exercise->interest = 10; // 10% intérêt
    $exercise->inscription_amount = 5000;
    $exercise->social_crown_amount = 10000;
    $exercise->penalty_rate = 15; // 15% pénalité (Taux m)
    $exercise->administrator_id = 1;
    if (!$exercise->save()) throw new Exception("Erreur création exercice: " . json_encode($exercise->errors));
    echo "  > Exercice de test créé (Année: 2099, Taux Pénalité: 15%)\n";

    // Créer un membre de test
    $member = new Member();
    $member->inscription = true;
    $member->social_crown = true;
    $member->active = true;
    $member->user_id = 1; // On lie à l'admin pour simplifier
    if (!$member->save()) throw new Exception("Erreur création membre");
    echo "  > Membre de test créé (ID: $member->id)\n";

    // ---------------------------------------------------------
    // 2. EPARGNE - Mise en place du capital
    // ---------------------------------------------------------
    echo "\n[2/6] Constitution de l'épargne...\n";
    
    // Créer Session 1
    $s1 = createSession($exercise->id, '2099-01-01');
    echo "  > Session 1 créée (Janvier)\n";

    // Ajouter 500 000 d'épargne
    $saving = new Saving();
    $saving->member_id = $member->id;
    $saving->session_id = $s1->id;
    $saving->amount = 500000;
    $saving->administrator_id = 1;
    $saving->save();
    echo "  > Epargne de 500 000 XAF ajoutée.\n";

    // ---------------------------------------------------------
    // 3. EMPRUNT - Test de la capacité et création
    // ---------------------------------------------------------
    echo "\n[3/6] Test de l'emprunt...\n";
    
    // Capacité pour 500 000 épargne => 500 000 * 5 = 2 500 000 max
    // On emprunte 1 000 000
    // Intérêt 10% = 100 000
    // Net perçu devrait être 900 000
    // Dette brute = 1 000 000

    $borrowing = new Borrowing();
    $borrowing->member_id = $member->id;
    $borrowing->session_id = $s1->id;
    $borrowing->amount = 1000000; // Dette brute
    $borrowing->interest = 10;
    $borrowing->administrator_id = 1;
    $borrowing->state = true;
    
    // Utiliser la logique calculateMaxBorrowingAmount si accessible, 
    // mais ici on simule l'enregistrement direct pour tester la suite (pénalités).
    // On assume que le contrôleur a fait son job de validation.
    
    if (!$borrowing->save()) throw new Exception("Erreur création emprunt");
    echo "  > Emprunt créé : 1 000 000 XAF (Brut)\n";
    echo "  > Intérêts précomptés (théorique) : 100 000 XAF\n";
    echo "  > Net perçu (théorique) : 900 000 XAF\n";

    // ---------------------------------------------------------
    // 4. SIMULATION TEMPS - Passage à +3 Mois
    // ---------------------------------------------------------
    echo "\n[4/6] Voyage dans le temps (+3 mois)...\n";
    // Créer Session 2, 3, 4
    // Emprunt fait en S1.
    // S1 -> S2 (1 mois), S2 -> S3 (2 mois), S3 -> S4 (3 mois révolus)
    $s2 = createSession($exercise->id, '2099-02-01');
    $s3 = createSession($exercise->id, '2099-03-01');
    $s4 = createSession($exercise->id, '2099-04-01'); // Nous sommes ici
    echo "  > Sessions Février, Mars, Avril créées.\n";

    echo "  > Exécution du gestionnaire de pénalités (3 mois)...\n";
    
    // Simuler le contexte Session Active = S4
    // PenaltyManager utilise Session::findOne(['active' => true])
    // On doit s'assurer que S4 est la seule active
    Yii::$app->db->createCommand("UPDATE session SET active=0")->execute();
    $s4->active = true;
    $s4->save();

    PenaltyManager::checkThreeMonthPenalties($exercise);

    // VÉRIFICATION
    // On doit avoir un prélèvement d'intérêts.
    // Intérêts = 100 000.
    // On doit trouver une épargne négative de -100 000
    $penaltySaving = Saving::find()
        ->where(['member_id' => $member->id, 'amount' => -100000])
        ->one();
    
    if ($penaltySaving) {
        echo "  > [SUCCES] Pénalité détectée ! Une déduction de 100 000 XAF a été trouvée sur l'épargne.\n";
    } else {
        echo "  > [ECHEC] Aucune pénalité trouvée sur l'épargne.\n";
    }

    // ---------------------------------------------------------
    // 5. SIMULATION TEMPS - Passage à +6 Mois
    // ---------------------------------------------------------
    echo "\n[5/6] Voyage dans le temps (+6 mois)...\n";
    // Créer S5, S6, S7
    createSession($exercise->id, '2099-05-01');
    createSession($exercise->id, '2099-06-01');
    $s7 = createSession($exercise->id, '2099-07-01'); // Nous sommes là (6 mois après S1)
    
    Yii::$app->db->createCommand("UPDATE session SET active=0")->execute();
    $s7->active = true;
    $s7->save();
    
    echo "  > Exécution du gestionnaire de pénalités (6 mois)...\n";
    
    // Cas de test :
    // Epargne initiale : 500 000
    // Pénalité 3 mois : -100 000
    // Reste Epargne : 400 000
    // Dette Restante : 1 000 000
    // Seuil Couverture : 5 * 1 000 000 = 5 000 000
    // 400 000 < 5 000 000 => ALERTE ATTENDUE
    // Pénalité Suggérée (15%) : 1 000 000 * 0.15 = 150 000
    
    // On va capturer les logs (simulation car on ne peut pas lire les logs fichiers facilement ici)
    // On va appeler la fonction et vérifier qu'elle ne plante pas.
    // Dans le vrai monde, l'user verra le log.
    
    PenaltyManager::checkSixMonthPenalties($exercise);
    echo "  > Vérification effectuée (Voir les logs application pour 'ALERTE: Emprunt #... Pénalité suggérée... 150 000 XAF').\n";

    // ---------------------------------------------------------
    // 6. NETTOYAGE
    // ---------------------------------------------------------
    echo "\n[6/6] Nettoyage...\n";
    // Rollback pour ne rien garder en base
    $transaction->rollBack();
    echo "  > Base de données restaurée (Rollback effectué).\n";
    echo "\n------------------------------------------------------\n";
    echo "RESULTAT GLOBAL : Le scénario s'est exécuté sans erreur fatale.\n";
    echo "Si vous avez vu le message [SUCCES] ci-dessus, la logique fonctionne.\n";

} catch (Exception $e) {
    $transaction->rollBack();
    echo "\n[ERREUR FATALE] : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

/**
 * Helper to create session
 */
function createSession($exerciseId, $date) {
    $s = new Session();
    $s->exercise_id = $exerciseId;
    $s->date = $date;
    $s->active = false;
    $s->administrator_id = 1;
    if (!$s->save()) throw new Exception("Erreur session $date");
    return $s;
}
