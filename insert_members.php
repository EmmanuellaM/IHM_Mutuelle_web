<?php
// insert_members.php - Insérer les 12 membres dans la base de données
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\User;
use app\models\Member;

echo "=== INSERTION DES 12 MEMBRES ===\n\n";

// Liste des membres
$members = [
    ['nom' => 'ATOCK', 'prenom' => 'STEPHANE'],
    ['nom' => 'AWONO O.', 'prenom' => 'CHARLES'],
    ['nom' => 'BATCHAKUI', 'prenom' => 'Barnabé'],
    ['nom' => 'BEDA', 'prenom' => 'TIBI'],
    ['nom' => 'BELOBO', 'prenom' => 'BELOBO Didier'],
    ['nom' => 'BIDOUNG', 'prenom' => 'JEAN CALVIN'],
    ['nom' => 'BITANG A', 'prenom' => 'ZIEM DANIEL'],
    ['nom' => 'BIYEME', 'prenom' => 'Florent'],
    ['nom' => 'BOUETOU B.', 'prenom' => 'THOMAS'],
    ['nom' => 'BOYOMO O.', 'prenom' => 'Marthe'],
    ['nom' => 'CHANA', 'prenom' => 'Anne Marie'],
    ['nom' => 'DJOTIO', 'prenom' => 'Thomas'],
];

$db = Yii::$app->db;
$transaction = $db->beginTransaction();

try {
    $count = 0;
    
    foreach ($members as $memberData) {
        // Créer l'utilisateur
        $user = new User();
        $user->name = $memberData['nom'];
        $user->first_name = $memberData['prenom'];
        
        // Générer un email basé sur le nom
        $emailBase = strtolower(str_replace([' ', '.'], '', $memberData['nom']));
        $user->email = $emailBase . '@mutuelle.cm';
        
        // Mot de passe par défaut : "mutuelle123"
        $user->password = Yii::$app->security->generatePasswordHash('mutuelle123');
        
        if ($user->save()) {
            // Créer le membre
            $member = new Member();
            $member->user_id = $user->id;
            $member->active = false; // Inactif par défaut
            $member->inscription = 0;
            $member->social_crown = 0;
            
            if ($member->save()) {
                $count++;
                echo "✅ Membre {$count}/12 : {$user->name} {$user->first_name}\n";
                echo "   Email : {$user->email}\n";
                echo "   Mot de passe : mutuelle123\n\n";
            } else {
                echo "❌ Erreur création membre pour {$user->name}\n";
                print_r($member->errors);
            }
        } else {
            echo "❌ Erreur création utilisateur pour {$memberData['nom']}\n";
            print_r($user->errors);
        }
    }
    
    $transaction->commit();
    
    echo "\n✅ {$count} membres insérés avec succès !\n";
    echo "\n📝 Informations de connexion :\n";
    echo "   Mot de passe pour tous : mutuelle123\n";
    echo "   Emails générés automatiquement\n";
    echo "\n⚠️  Les membres sont INACTIFS par défaut\n";
    echo "   Ils doivent payer inscription + fond social pour devenir actifs\n";
    
} catch (Exception $e) {
    $transaction->rollBack();
    echo "\n❌ Erreur : " . $e->getMessage() . "\n";
}
