<?php
$servername = "inst-db-papeleria-cvf1.c16ku0042y73.us-east-1.rds.amazonaws.com";
$username = "jhonfre";  // Cambia esto si tienes otro usuario
$password = "DB_jhonfre";  // Contraseña
$dbname = "empresa_actividades";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
