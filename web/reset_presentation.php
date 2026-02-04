<?php
/**
 * Script de réinitialisation et population pour la présentation (Version Web)
 * Accessible via l'URL /reset_presentation.php
 */

// Ajustement des chemins pour le dossier web/
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Utilisation de la config Web pour l'affichage (mais on n'utilise pas l'app web complète pour éviter les conflits de routing)
// On utilise une petite astuce: on charge l'app console pour la logique mais on affiche du texte
$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

use app\models\User;
use app\models\Member;

// Simple sécurité basique pour éviter un reset accidentel par un crawler
$key = isset($_GET['key']) ? $_GET['key'] : '';
if ($key !== 'presentation2026') {
    die("<h1>Accès refusé</h1><p>Veuillez fournir la clé de sécurité : ?key=presentation2026</p>");
}

echo "<pre>";
echo "=== SCRIPT DE PRÉSENTATION : RESET & POPULATION (WEB) ===\n\n";

$membersData = [
    ["Yatat Djeumen", "Ivric Valaire", "yatat.valaire@gmail.com", "675305726", "NkoaBang"],
    ["OLIVIER VIDEME BOSSOU", "", "obossou@gmail.com", "679921168", "Yaoundé"],
    ["Elime bouboama", "Aimé", "aelime2001@yahoo.fr", "699892009", "Nkongoa par mfou"],
    ["NGONO MVONDO epse ANDJOCK", "Rachel Raïssa", "rachel.ngono@univ-yaounde1.cm", "699653065", "Nkoabang face Total Nlobisson"],
    ["MBINKAR", "EDWIN NYUYSEVER", "mbinkaren@yahoo.fr", "677469921", "Nkolbisson"],
    ["MANDENG MANDENG", "LUCIEN", "mandengl@yahoo.fr", "671049173", "YAOUNDE MONTEE JOUVENCE"],
    ["TAKOU", "Etienne", "etienne.takou@gmail.com", "678582850", "Rue Damase"],
    ["VOUFO", "Joseph", "voufojo1@gmail.com", "658184036", "Fougerolles"],
    ["Batchakui", "Bernabé", "bernabe.batchakui@univ-yaounde1.cm", "677602012", "Simbock"],
    ["BIDOUNG", "Jean Calvin", "bidoung@yahoo.com", "696488503", "BIYEM ASSI"],
    ["FANDIO", "BRIDINETTE", "sbridine@yahoo.fr", "678933819", "Yaoundé-Etoug-Ebe"],
    ["TAGOUDJEU", "JACQUES", "jtagoudjeu@gmail.com", "699814218", "Nkolbisson-Yaoundé"],
    ["Onanena", "Raissa", "raissa.onanena@univ-yaounde1.cm", "675239224", "Ahala"],
    ["Tiogning epse Djiogue", "Lauraine", "lauraine.djiogue@univ-yaounde1.cm", "677669854", "Nkolbisson"],
    ["MOUKOUOP NGUENA", "IBRAHIM", "imoukouo@gmail.com", "699809291", "Soa, Ebogo 1, lieu dit deux Étages"],
    ["TCHOMGO FELENOU", "Emmanuel", "mekempi@yahoo.fr", "699805936", "Yaoundé"],
    ["TEWA", "Jean Jules", "tewajules@gmail.com", "677711369", "Nkolfoulou"],
    ["TALE KALACHI", "Hervé", "hervekalachi@gmail.com", "695381750", "Tsinga Village, Entrée Lycée"],
    ["Talla", "André", "atalla16034@gmail.com", "671216133", "Bitotol"]
];

$db = Yii::$app->db;
$transaction = $db->beginTransaction();

try {
    // 1. NETTOYAGE
    echo "1. Nettoyage de la base de données...\n";
    
    $tables = [
        'renflouement', 'help', 'refund', 'borrowing_saving', 'borrowing', 
        'saving', 'contribution_tontine', 'tontine', 'contribution', 
        'agape', 'session', 'exercise'
    ];
    
    foreach ($tables as $table) {
        $db->createCommand("DELETE FROM {$table}")->execute();
    }
    // Suppression spécifique Agapé3 ignorée pour éviter erreur transaction Postgres
    // (sur Postgres, une erreur dans une transaction annule toute la transaction)


    $memberUserIds = $db->createCommand('SELECT user_id FROM member')->queryColumn();
    $db->createCommand('DELETE FROM member')->execute();
    
    if (!empty($memberUserIds)) {
        $db->createCommand()->delete('user', ['id' => $memberUserIds])->execute();
    }
    
    echo "   ✓ Base de données nettoyée (Administrateurs conservés)\n\n";
    
    // 2. INSERTION
    echo "2. Insertion des " . count($membersData) . " membres...\n";
    
    $passwordHash = Yii::$app->security->generatePasswordHash('Membre123');
    $count = 0;
    
    foreach ($membersData as $index => $data) {
        // Nettoyage téléphone
        $phone = preg_replace('/[^0-9]/', '', $data[3]);
        if (strlen($phone) > 9) $phone = substr($phone, 0, 9);

        $user = new User();
        $user->name = trim($data[0]);
        $user->first_name = trim($data[1]);
        $user->email = trim($data[2]);
        $user->password = $passwordHash;
        $user->type = 'MEMBER'; 
        
        // CORRECTION: Utilisation des attributs corrects (voir User et Member models)
        // User: name, first_name, email, password, tel, address
        // Member: user_id, username, active, inscription, social_crown
        
        $user->tel = $phone;
        $user->address = trim($data[4]);
        
        if (!$user->save()) {
             echo "❌ Erreur User ({$data[0]}): " . json_encode($user->errors) . "\n";
             continue;
        }
        
        $member = new Member();
        $member->user_id = $user->id;
        $member->username = explode('@', $user->email)[0]; // Génération pseudo via email
        // Si le pseudo existe déjà, on ajoute un suffixe (rare ici mais bon)
        if (Member::find()->where(['username' => $member->username])->exists()) {
             $member->username .= rand(10,99);
        }
        
        $member->active = false;
        $member->inscription = 0; // Pas payé
        $member->social_crown = 0; // Pas payé
        
        if ($member->save()) {
            $count++;
            echo "   ✅ {$user->name} {$user->first_name} ({$user->email})\n";
        } else {
             echo "❌ Erreur Member ({$data[0]}): " . json_encode($member->errors) . "\n";
        }
    }
    
    $transaction->commit();
    echo "\n=== Terminé : {$count} membres créés ===\n";
    echo "Mot de passe unique : Membre123\n";
    echo "</pre>";

} catch (\Exception $e) {
    $transaction->rollBack();
    echo "\n❌ ERREUR CRITIQUE : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
