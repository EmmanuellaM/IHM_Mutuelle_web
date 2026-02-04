<?php
require 'vendor/autoload.php';

$reflection = new ReflectionClass('app\controllers\GuestController');

echo "Classe: " . $reflection->getName() . "\n\n";
echo "Méthodes d'action trouvées:\n";

foreach($reflection->getMethods() as $method) {
    if(strpos($method->name, 'action') === 0) {
        echo "  - " . $method->name . "\n";
    }
}
