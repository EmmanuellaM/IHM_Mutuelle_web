<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => getenv('MYSQL_URL') ?: 'mysql:host=localhost;dbname=mutuelle',
    'username' => getenv('MYSQLUSER'),
    'password' => getenv('MYSQLPASSWORD'),
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
