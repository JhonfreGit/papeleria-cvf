<?php
require_once 'config_bd/db_connection.php';

// Función para verificar si el usuario es administrador
function verificarSesionAdministrador() {
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'administrador') {
        header('Location: index.php');
        exit();
    }
}

// Función para crear un nuevo usuario
function crearUsuario($identification_number, $username, $email, $password, $role) {
    $pdo = getDBConnection(); // Usamos PDO en lugar de mysqli
    
    // Hash de la contraseña
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
    
    // Preparar la consulta para insertar un nuevo usuario usando PDO
    $stmt = $pdo->prepare("INSERT INTO users (identification_number, username, password, email, role) 
                           VALUES (?, ?, ?, ?, ?)");
    
    // Ejecutar la consulta con los parámetros
    if ($stmt->execute([$identification_number, $username, $passwordHashed, $email, $role])) {
        return "Usuario creado exitosamente.";
    } else {
        return "Error al crear el usuario.";
    }
}
?>
