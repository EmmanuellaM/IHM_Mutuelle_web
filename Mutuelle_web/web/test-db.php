<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $dsn = 'mysql:host=sql.freedb.tech;port=3306;dbname=freedb_mutuelle';
    $username = 'freedb_wandji';
    $password = 'AfC9zKpNmX2P%T$';
    
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données Freedb!";
    
    // Test simple pour vérifier l'accès aux données
    $stmt = $pdo->query("SHOW TABLES");
    echo "<br><br>Tables dans la base de données :<br>";
    while ($row = $stmt->fetch()) {
        echo $row[0] . "<br>";
    }
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}