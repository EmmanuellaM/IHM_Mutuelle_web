<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=sql.freedb.tech;port=3306;dbname=freedb_mutuelle',
    'username' => getenv('DB_USERNAME') ?: 'freedb_wandji',
    'password' => getenv('DB_PASSWORD') ?: 'AfC9zKpNmX2P%T$',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
