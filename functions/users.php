<?php
require_once 'config_bd/db_connection.php';

// Función para autenticar usuario
function authenticateUser($username, $password) {
    $pdo = getDBConnection(); // Usar PDO para la conexión
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verificar que la contraseña ingresada coincida con la contraseña almacenada (hasheada)
        if ($password == $user['password']) {
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Redirigir según el rol del usuario
            if ($user['role'] == 'administrador') {
                header('Location: admin_dashboard.php');
                exit();
            } elseif ($user['role'] == 'empleado' || $user['role'] == 'jefe') {
                header('Location: dashboard.php');
                exit();
            }
        } else {
            return "La contraseña es incorrecta.";
        }
    } else {
        return "Usuario no encontrado.";
    }
}

// Función para restablecer la contraseña
function resetPassword($username, $email, $newPassword) {
    $pdo = getDBConnection();
    $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT); // Hash de la nueva contraseña

    // Actualizar la contraseña del usuario en la base de datos
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
    if ($stmt->execute([$newPasswordHashed, $username, $email])) {
        return "La contraseña ha sido actualizada correctamente.";
    } else {
        return "No se encontraron usuarios con esos datos.";
    }
}

// Función para obtener los datos del usuario
function getUserData($user_id) {
    $pdo = getDBConnection(); // Usar PDO para la conexión
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        return $user; // Devuelve todos los datos del usuario
    } else {
        return null; // Si no se encuentra el usuario, devuelve null
    }
}

function updateUserData($userId, $email, $password, $profileImageUri) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
    return $stmt->execute([$profileImageUri, $userId]);
}
?>
