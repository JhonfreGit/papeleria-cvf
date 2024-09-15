<?php
session_start();
include 'db_connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user) {
        if ($password == $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirigir según el rol
            if ($user['role'] == 'administrador') {
                header('Location: admin_dashboard.php'); // Redirige al panel de administrador
            } else {
                header('Location: dashboard.php'); // Redirige al panel de usuario
            }
        } else {
            $error = "La contraseña es incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Papelería CVF</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Papelería CVF</h1>
        <p>Bienvenido, por favor ingresa tus credenciales para acceder a las funcionalidades del sistema.</p>

        <form action="index.php" method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Iniciar Sesión</button>
        </form>
        
        <p class="text-muted">¿Olvidaste tus credenciales? <a href="forgot_password.php">Ajustar credenciales</a></p>
        
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
