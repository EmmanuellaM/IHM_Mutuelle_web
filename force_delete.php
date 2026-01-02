<?php
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

try {
    $db = Yii::$app->db;
    $db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->execute();
    
    // 1. Delete Members
    $db->createCommand("TRUNCATE TABLE member")->execute();
    echo "Members truncated.\n";
    // 1b. Delete other tables just in case
    $db->createCommand("TRUNCATE TABLE renflouement")->execute();
     $db->createCommand("TRUNCATE TABLE agape")->execute();
     $db->createCommand("TRUNCATE TABLE help")->execute();
     $db->createCommand("TRUNCATE TABLE contribution")->execute();
    
    // 2. Delete Users who are not admins
    // Get Admin User IDs
    $adminUserIds = (new \yii\db\Query())
        ->select('user_id')
        ->from('administrator')
        ->column();
        
    if (!empty($adminUserIds)) {
        $deleted = $db->createCommand()->delete('user', ['not in', 'id', $adminUserIds])->execute();
        echo "Deleted $deleted non-admin users.\n";
    } else {
        echo "WARNING: No administrators found! NOT deleting users to prevent lockout.\n";
    }

    $db->createCommand("SET FOREIGN_KEY_CHECKS = 1")->execute();
    echo "Done.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
