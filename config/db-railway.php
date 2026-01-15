<?php
/**
 * Configuration de la base de données pour Railway
 * Utilise les variables d'environnement fournies par Railway MySQL
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
];
