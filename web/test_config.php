<?php
// Test depuis le contexte web
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

header('Content-Type: text/plain');

echo "=== TEST DEPUIS LE CONTEXTE WEB ===\n\n";

echo "Fichier config chargé: " . realpath(__DIR__ . '/../config/web.php') . "\n\n";

echo "Alias:\n";
echo "@guest.connexion = " . Yii::getAlias('@guest.connexion') . "\n";
echo "@guest.administrator_form = " . Yii::getAlias('@guest.administrator_form') . "\n\n";

echo "Test de routing:\n";
$controller = Yii::$app->createController('guest/adminlogin');
if ($controller !== false) {
    echo "✓ Route 'guest/adminlogin' reconnue\n";
    echo "Contrôleur: " . get_class($controller[0]) . "\n";
    echo "Action: " . $controller[1] . "\n";
} else {
    echo "✗ Route 'guest/adminlogin' NON reconnue!\n";
}

echo "\nMéthodes d'action dans GuestController:\n";
$reflection = new ReflectionClass('app\controllers\GuestController');
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
foreach ($methods as $method) {
    if (strpos($method->name, 'action') === 0 && $method->class === 'app\controllers\GuestController') {
        echo "  - " . $method->name . "\n";
    }
}
