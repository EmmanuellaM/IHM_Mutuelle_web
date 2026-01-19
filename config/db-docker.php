<?php
/**
 * Configuration de la base de données pour Docker
 * Supporte Docker local et Railway
 */

// Utiliser les variables Railway si disponibles, sinon les valeurs Docker locales
$host = getenv('DB_HOST') ?: 'mysql';
$dbname = getenv('DB_NAME') ?: 'mutuelle_web';
$username = getenv('DB_USER') ?: 'mutuelle_user';
$password = getenv('DB_PASSWORD') ?: 'mutuelle_pass';
$port = getenv('DB_PORT') ?: '3306';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host={$host};port={$port};dbname={$dbname}",
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
];
