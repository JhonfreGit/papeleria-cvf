<?php
session_start();
require_once 'functions/users.php';  // Incluir funciones para usuarios

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Obtén los datos del usuario
$username = $_SESSION['username'];
$role = $_SESSION['role'];  // Asegúrate de tener el rol guardado en la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Papelería CVF</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Bienvenido, <?php echo htmlspecialchars($username); ?></h1>

        <ul>
            <li><a href="edit_profile.php" class="button">Cambiar datos de su perfil</a></li>
            <li>
                <?php if ($role === 'jefe'): ?>
                    <a href="dashboard_consult.php" class="button">Consulta de actividades</a>
                <?php elseif ($role === 'empleado'): ?>
                    <a href="dashboard_register.php" class="button">Registro de actividades</a>
                <?php endif; ?>
            </li>
            <li><a href="logout.php" class="button">Salir de su sesión</a></li>
        </ul>
    </div>
</body>
</html>
