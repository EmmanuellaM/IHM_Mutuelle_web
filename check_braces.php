<?php
$content = file_get_contents('controllers/GuestController.php');
$open = substr_count($content, '{');
$close = substr_count($content, '}');
echo "Accolades ouvrantes: $open\n";
echo "Accolades fermantes: $close\n";
echo "Différence: " . ($open - $close) . "\n";

if ($open != $close) {
    echo "\n⚠️ PROBLÈME: Les accolades ne sont pas équilibrées!\n";
} else {
    echo "\n✓ Les accolades sont équilibrées\n";
}
