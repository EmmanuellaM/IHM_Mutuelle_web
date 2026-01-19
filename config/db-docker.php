<?php
/**
 * Configuration de la base de données pour Docker
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=mysql;dbname=mutuelle_web',
    'username' => 'mutuelle_user',
    'password' => 'mutuelle_pass',
    'charset' => 'utf8',
];
