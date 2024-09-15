<?php
session_start();
include 'db_connection.php';

if ($_SESSION['role'] != 'administrador') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identification_number = $_POST['identification_number'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Insertar el nuevo usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO users (identification_number, username, password, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $identification_number, $username, $password, $email, $role);
    
    if ($stmt->execute()) {
        $success = "Usuario creado exitosamente.";
    } else {
        $error = "Error al crear el usuario.";
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

        <?php if (isset($success)) { echo "<p class='success'>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    </div>
</body>
</html>
