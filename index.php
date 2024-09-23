<?php
require_once 'functions/users.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Llamar a la función de autenticación
    $error = authenticateUser($username, $password);
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
        
        <?php if ($error) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>