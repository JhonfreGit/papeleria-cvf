<?php
include 'functions/admin.php';  // Incluir el archivo de funciones

// Verificar que el usuario sea administrador
verificarSesionAdministrador();

// Variables para almacenar mensajes de error o éxito
$success = '';
$error = '';

// Procesar el formulario de creación de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identification_number = $_POST['identification_number'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Crear el usuario y obtener el mensaje de éxito o error
    $mensaje = crearUsuario($identification_number, $username, $email, $password, $role);

    if ($mensaje === "Usuario creado exitosamente.") {
        $success = $mensaje;
    } else {
        $error = $mensaje;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador - Crear Usuario</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Agregar Nuevo Usuario</h2>

        <form action="admin_dashboard.php" method="POST">
            <label for="identification_number">Número de Identificación:</label>
            <input type="text" id="identification_number" name="identification_number" required>
            
            <label for="username">Nombre de Usuario:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Correo Corporativo:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Rol del Usuario:</label>
            <select id="role" name="role">
                <option value="empleado">Empleado</option>
                <option value="jefe">Jefe</option>
                <option value="administrador">Administrador</option>
            </select>

            <button type="submit">Crear Usuario</button>
        </form>

        <!-- Mostrar mensajes de éxito o error -->
        <?php if ($success): ?>
            <p class='success'><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class='error'><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
