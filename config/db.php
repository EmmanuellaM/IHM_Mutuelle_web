<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('DB_DSN') ?: 'mysql:host=sql202.infinityfree.com;port=3306;dbname=if0_38168993_mutuelle',
    'username' => getenv('DB_USERNAME') ?: 'if0_38168993',
    'password' => getenv('DB_PASSWORD') ?: 'ODyq34I3wKuGRRN',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
