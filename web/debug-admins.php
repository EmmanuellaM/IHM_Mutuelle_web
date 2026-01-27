<?php
/**
 * Script to debug administrator root status
 * Run via web: /web/debug-admins.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

echo "<h1>Administrator Debug</h1>";

try {
    $admins = \app\models\Administrator::find()->all();
    
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Root Status</th><th>Action</th></tr>";
    
    foreach($admins as $a) {
        echo "<tr>";
        echo "<td>" . $a->id . "</td>";
        echo "<td>" . htmlspecialchars($a->username) . "</td>";
        echo "<td>" . ($a->root ? '<strong style="color:green">TRUE</strong>' : '<span style="color:red">FALSE</span>') . "</td>";
        echo "<td>";
        if (!$a->root) {
            echo "<form method='post' style='display:inline;'>";
            echo "<input type='hidden' name='admin_id' value='" . $a->id . "'>";
            echo "<input type='hidden' name='action' value='make_root'>";
            echo "<input type='hidden' name='" . Yii::$app->request->csrfParam . "' value='" . Yii::$app->request->csrfToken . "'>";
            echo "<button type='submit'>Make Root</button>";
            echo "</form>";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Handle form submission
    if (Yii::$app->request->isPost && isset($_POST['action']) && $_POST['action'] === 'make_root') {
        $id = (int)$_POST['admin_id'];
        $admin = \app\models\Administrator::findOne($id);
        if ($admin) {
            $admin->root = true;
            if ($admin->save()) {
                echo "<p style='color:green'>Success: Administrator " . htmlspecialchars($admin->username) . " is now ROOT.</p>";
                echo "<meta http-equiv='refresh' content='2'>";
            } else {
                echo "<p style='color:red'>Error saving administrator: " . json_encode($admin->errors) . "</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}
