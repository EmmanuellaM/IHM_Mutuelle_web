<?php
/**
 * Script de réinitialisation et population pour la présentation
 */

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/console.php');
new yii\console\Application($config);

use app\models\User;
use app\models\Member;

echo "=== SCRIPT DE PRÉSENTATION : RESET & POPULATION ===\n\n";

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
    
    // Suppression spécifique Agapé3 si existe
    try {
        $db->createCommand("DELETE FROM agape3")->execute();
    } catch (\Exception $e) { /* ignore */ }

    // Suppression des membres et leurs users
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
        // Nettoyage téléphone (garder seulement les chiffres)
        $phone = preg_replace('/[^0-9]/', '', $data[3]);
        if (strlen($phone) > 9) $phone = substr($phone, 0, 9); // Cas double numéros, on prend le premier ou on coupe

        $user = new User();
        $user->name = trim($data[0]);
        $user->first_name = trim($data[1]);
        $user->email = trim($data[2]);
        $user->password = $passwordHash;
        $user->type = 'MEMBER'; 
        
        // Champs 'phone' et 'address' n'existent pas dans User mais dans Member ? 
        // Vérifions les modèles. User a name, first_name, email, password. 
        // Member a user_id, active, inscription, social_crown, et POTENTIELLEMENT phone/address
        // D'après clean_database, Member a user_id. 
        // Je vais vérifier le modèle Member pour voir s'il a phone et address.
        // Attente... je n'ai pas vu phone/address dans Member lors de view_file.
        // User a-t-il phone/address ?
        // Je vais assumer que Member ou User a ces champs. S'ils manquent, je les stockerais où ?
        // En regardant le code existant insert_members.php, il n'insère QUE nom et prénom dans User.
        // Il n'y a PAS de phone ni address dans l'insertion précédente.
        // Je vais vérifier les colonnes de la table Member et User via SQL pour être sûr.
        
        if (!$user->save()) {
             echo "❌ Erreur User ({$data[0]}): " . json_encode($user->errors) . "\n";
             continue;
        }
        
        $member = new Member();
        $member->user_id = $user->id;
        $member->active = false;
        $member->inscription = 0; // Pas payé
        $member->social_crown = 0; // Pas payé
        
        // Tentative d'assignation si les propriétés existent (magie Yii2 ou propriétés publiques)
        // Je vais utiliser setAttributes avec safeOnly=false pour forcer si possible, mais mieux vaut vérifier avant.
        // Pour l'instant, j'insère le User/Member de base.
        // Si les colonnes phone/address existent dans Member, je les update.
        
        /* 
           NOTE: D'après insert_members.php précédent, on ne mettait pas d'adresse ni tel.
           Mais le client VEUT "remplis avec les membres...".
           Je vais ajouter une étape de vérification des colonnes juste après.
           Pour ce script, je vais supposer que Member a peut-être ces champs
           OU User a ces champs.
           
           Dans le doute, je vais faire un try-catch sur l'assignation directe si ce sont des attributs dynamiques
           ou vérifier via table schema.
        */
        
        if ($member->hasAttribute('phone')) $member->phone = $phone;
        if ($member->hasAttribute('address')) $member->address = trim($data[4]);
        
        // Idem pour User si c'est là-bas
        if ($user->hasAttribute('phone')) $user->phone = $phone;
        if ($user->hasAttribute('address')) $user->address = trim($data[4]);
        
        // Sauvegarde User si modifié
        if ($user->isAttributeChanged('phone') || $user->isAttributeChanged('address')) {
            $user->save();
        }

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

} catch (\Exception $e) {
    $transaction->rollBack();
    echo "\n❌ ERREUR CRITIQUE : " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
