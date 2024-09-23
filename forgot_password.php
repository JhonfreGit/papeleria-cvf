<?php
require_once 'functions/users.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];
    $newPassword = $_POST['new_password'];

    // Llamar a la función resetPassword
    $response = resetPassword($cedula, $email, $newPassword);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Papelería CVF</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Restablecer Contraseña</h2>
        <form action="forgot_password.php" method="POST">
            <label for="cedula">Número de Cédula:</label>
            <input type="text" id="cedula" name="cedula" required>
            
            <label for="email">Email Corporativo:</label>
            <input type="email" id="email" name="email" required>

            <label for="new_password">Nueva Contraseña:</label>
            <input type="password" id="new_password" name="new_password" required>

            <button type="submit">Actualizar Contraseña</button>
        </form>

        <?php if (isset($response)) { echo "<p class='message'>$response</p>"; } ?>
    </div>
</body>
</html>
