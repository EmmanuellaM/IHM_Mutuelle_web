<?php
/**
 * Configuration de la base de données pour PostgreSQL (Render)
 */

// Parser l'URL PostgreSQL si disponible
if (getenv('DATABASE_URL')) {
    $url = parse_url(getenv('DATABASE_URL'));
    return [
        'class' => 'yii\db\Connection',
        'dsn' => sprintf(
            'pgsql:host=%s;port=%s;dbname=%s', 
            $url['host'], 
            isset($url['port']) ? $url['port'] : '5432',
            ltrim($url['path'], '/')
        ),
        'username' => $url['user'],
        'password' => $url['pass'],
        'charset' => 'utf8',
    ];
}

// Sinon utiliser les variables séparées
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'mutuelle_web';
$username = getenv('DB_USER') ?: 'mutuelle_user';
$password = getenv('DB_PASSWORD') ?: '';
$port = getenv('DB_PORT') ?: '5432';

return [
    'class' => 'yii\db\Connection',
    'dsn' => "pgsql:host={$host};port={$port};dbname={$dbname}",
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
];
