<?php
require_once '../config/db.php';

function authenticateUser($username, $password) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($password == $user['password']) {
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];

            // Redirigir según el rol
            if ($user['role'] == 'administrador') {
                header('Location: admin_dashboard.php');
                exit();
            } elseif ($user['role'] == 'empleado') {
                header('Location: dashboard_register.php');
                exit();
            } elseif ($user['role'] == 'jefe') {
                header('Location: dashboard_consult.php');
                exit();
            }
        } else {
            return "La contraseña es incorrecta.";
        }
    } else {
        return "Usuario no encontrado.";
    }
}

function resetPassword($cedula, $email, $newPassword) {
    $pdo = getDBConnection();
    $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
    if ($stmt->execute([$newPasswordHashed, $cedula, $email])) {
        return "La contraseña ha sido actualizada correctamente.";
    } else {
        return "No se encontraron usuarios con esos datos.";
    }
}

?>
