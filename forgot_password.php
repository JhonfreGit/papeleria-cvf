<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ? AND email = ?");
    $stmt->bind_param("sss", $new_password, $cedula, $email);
    if ($stmt->execute()) {
        $success = "La contraseña ha sido actualizada correctamente.";
    } else {
        $error = "No se encontraron usuarios con esos datos.";
    }
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
        
        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
