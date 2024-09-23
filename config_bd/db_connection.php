<?php
function getDBConnection() {
    $host = 'inst-db-papeleria-cvf1.c16ku0042y73.us-east-1.rds.amazonaws.com';
    $dbname = 'empresa_actividades';
    $user = 'jhonfre';
    $password = 'DB_jhonfre';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die();
    }
}
?>
