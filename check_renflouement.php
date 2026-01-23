<?php
// check_renflouement.php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

use app\models\Renflouement;
use app\models\Exercise;

echo "\n--- Vérification Dépenses et Renflouements ---\n";

// 1. Chercher explicitement l'exercice 2026
$targetYear = 2026;
$ex2026 = Exercise::findOne(['year' => $targetYear]);

if ($ex2026) {
    echo "Exercice $targetYear trouvé (ID: " . $ex2026->id . ") | Actif: " . ($ex2026->active ? 'Oui' : 'Non') . "\n";
    
    // 2. Calculer les Agapes
    $sessions = \app\models\Session::find()->select('id')->where(['exercise_id' => $ex2026->id])->column();
    if (empty($sessions)) {
         $agapeSum = 0;
         echo "Aucune session trouvée pour cet exercice.\n";
    } else {
         $agapeSum = \app\models\Agape::find()->where(['session_id' => $sessions])->sum('amount');
    }
    echo "Total Agapes: " . ($agapeSum ?? 0) . " XAF\n";
    
    // 3. Calculer les Aides (Logic from Exercise::getTotalHelpsFromSocialFund)
    $startDate = $targetYear . '-01-01 00:00:00';
    $endDate = $targetYear . '-12-31 23:59:59';
    $helpSum = \app\models\Help::find()
        ->where(['between', 'created_at', $startDate, $endDate])
        ->sum('amount_from_social_fund');
        
    echo "Total Aides (Fonds Social): " . ($helpSum ?? 0) . " XAF\n";
    
    // 4. Total et Calcul théorique
    $total = ($agapeSum ?? 0) + ($helpSum ?? 0);
    echo "Total Dépenses Calculées: " . $total . " XAF\n";
    
    $activeMembers = \app\models\Member::find()->where(['active' => 1])->count();
    echo "Membres Actifs: " . $activeMembers . "\n";
    
    if ($activeMembers > 0) {
        $amountPerMember = ceil($total / $activeMembers);
        echo "Calcul Renflouement Théorique par membre: " . $amountPerMember . "\n";
    } else {
        echo "Aucun membre actif.\n";
    }
    
    // 5. Vérifier les renflouements réellement créés pour cet exercice
    echo "\n--- Renflouements Réels en DB (Liés à Ex $targetYear) ---\n";
    $realRenflouements = Renflouement::find()->where(['exercise_id' => $ex2026->id])->all();
    if (empty($realRenflouements)) {
        echo "Aucun renflouement trouvé en base de données avec exercise_id = " . $ex2026->id . ".\n";
        
        // Check if any exists at all
        $any = Renflouement::find()->count();
        echo "Total renflouements dans toute la table: $any\n";
    } else {
        echo count($realRenflouements) . " renflouements trouvés.\n";
        foreach ($realRenflouements as $r) {
            echo "- Membre " . $r->member_id . " : " . $r->amount . "\n";
        }
    }

} else {
    echo "Exercice $targetYear introuvable.\n";
}
