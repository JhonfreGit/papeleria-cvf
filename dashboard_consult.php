<?php
session_start();
include 'db_connection.php';  // Incluir la conexión a la base de datos

// Verifica si el usuario ha iniciado sesión y tiene el rol de jefe
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'jefe') {
    header('Location: index.php');
    exit();
}

// Variables para almacenar mensajes de error o éxito
$error = '';
$success = '';

// Obtener la lista de empleados
$stmt = $conn->prepare("SELECT id, username FROM users WHERE role = 'empleado'");
$stmt->execute();
$result = $stmt->get_result();
$empleados = [];
while ($row = $result->fetch_assoc()) {
    $empleados[] = $row;
}

// Procesar selección de empleado y obtener actividades
$selectedEmployee = null;
$activities = [];
if (isset($_POST['employee_id'])) {
    $selectedEmployee = $_POST['employee_id'];
    $stmt = $conn->prepare("SELECT date, hour, activity FROM activities WHERE user_id = ? ORDER BY date ASC, hour ASC");
    $stmt->bind_param("i", $selectedEmployee);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar actividades por empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Consultar actividades por empleado</h1>

        <form action="dashboard.php" method="POST">
            <label for="employee">Selecciona un empleado:</label>
            <select name="employee_id" id="employee" required>
                <option value="">Seleccionar empleado</option>
                <?php foreach ($empleados as $empleado): ?>
                    <option value="<?php echo $empleado['id']; ?>" <?php echo ($selectedEmployee == $empleado['id']) ? 'selected' : ''; ?>>
                        <?php echo $empleado['username']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Consultar actividades</button>
        </form>

        <!-- Mostrar actividades del empleado seleccionado debajo del formulario -->
        <?php if ($selectedEmployee && count($activities) > 0): ?>
            <h2>Actividades de <?php echo $empleados[array_search($selectedEmployee, array_column($empleados, 'id'))]['username']; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Actividad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $activity): ?>
                        <tr>
                            <td><?php echo $activity['date']; ?></td>
                            <td><?php echo $activity['hour']; ?></td>
                            <td><?php echo $activity['activity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($selectedEmployee): ?>
            <p>No se encontraron actividades para el empleado seleccionado.</p>
        <?php endif; ?>

        <!-- Mostrar mensajes de error o éxito -->
        <?php if ($error): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success-message"><?php echo $success; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
