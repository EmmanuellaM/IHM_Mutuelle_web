<?php
echo "=== DIAGNOSTIC COMPLET DU PROJET ===\n\n";

// 1. Vérifier que le fichier GuestController existe
echo "1. Vérification du fichier GuestController.php\n";
$controllerPath = __DIR__ . '/controllers/GuestController.php';
if (file_exists($controllerPath)) {
    echo "   ✓ Fichier existe\n";
    echo "   Taille: " . filesize($controllerPath) . " octets\n";
} else {
    echo "   ✗ Fichier n'existe pas!\n";
    exit(1);
}

// 2. Charger Yii2 et vérifier la classe
echo "\n2. Chargement de Yii2 et vérification de la classe\n";
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

try {
    $reflection = new ReflectionClass('app\controllers\GuestController');
    echo "   ✓ Classe GuestController chargée\n";
    echo "   Fichier: " . $reflection->getFileName() . "\n";
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Lister toutes les méthodes d'action
echo "\n3. Méthodes d'action trouvées:\n";
$methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
$actionMethods = [];
foreach ($methods as $method) {
    if (strpos($method->name, 'action') === 0 && $method->class === 'app\controllers\GuestController') {
        $actionMethods[] = $method->name;
        echo "   ✓ " . $method->name . "\n";
    }
}

if (empty($actionMethods)) {
    echo "   ✗ Aucune méthode d'action trouvée!\n";
}

// 4. Vérifier spécifiquement actionAdminlogin
echo "\n4. Vérification de actionAdminlogin:\n";
if (in_array('actionAdminlogin', $actionMethods)) {
    echo "   ✓ actionAdminlogin existe\n";
    $method = $reflection->getMethod('actionAdminlogin');
    echo "   Ligne de début: " . $method->getStartLine() . "\n";
    echo "   Ligne de fin: " . $method->getEndLine() . "\n";
} else {
    echo "   ✗ actionAdminlogin N'EXISTE PAS!\n";
}

// 5. Vérifier actionAdministratorForm
echo "\n5. Vérification de actionAdministratorForm:\n";
if (in_array('actionAdministratorForm', $actionMethods)) {
    echo "   ✓ actionAdministratorForm existe\n";
} else {
    echo "   ✗ actionAdministratorForm n'existe pas\n";
}

// 6. Vérifier les alias
echo "\n6. Vérification des alias:\n";
try {
    echo "   @guest.connexion = " . Yii::getAlias('@guest.connexion') . "\n";
    echo "   @guest.administrator_form = " . Yii::getAlias('@guest.administrator_form') . "\n";
} catch (Exception $e) {
    echo "   ✗ Erreur: " . $e->getMessage() . "\n";
}

// 7. Tester le routing
echo "\n7. Test du routing Yii2:\n";
$controller = Yii::$app->createController('guest/adminlogin');
if ($controller !== false) {
    echo "   ✓ Route 'guest/adminlogin' reconnue\n";
    echo "   Contrôleur: " . get_class($controller[0]) . "\n";
    echo "   Action: " . $controller[1] . "\n";
} else {
    echo "   ✗ Route 'guest/adminlogin' NON reconnue!\n";
}

echo "\n=== FIN DU DIAGNOSTIC ===\n";
