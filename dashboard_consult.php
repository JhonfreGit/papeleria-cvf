<?php
include 'functions/activities.php';  // Incluir el archivo de funciones

// Verificar que el usuario sea jefe
verificarSesionJefe();

// Variables para almacenar mensajes de error o éxito
$error = '';
$success = '';

// Obtener la lista de empleados
$empleados = obtenerEmpleados();

// Procesar selección de empleado y obtener actividades
$selectedEmployee = null;
$activities = [];
if (isset($_POST['employee_id'])) {
    $selectedEmployee = $_POST['employee_id'];
    $activities = obtenerActividadesPorEmpleado($selectedEmployee);
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
    <div id="consult-dashboard" class="container">
        <h1>Consultar actividades por empleado</h1>

        <form action="dashboard_consult.php" method="POST">
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
