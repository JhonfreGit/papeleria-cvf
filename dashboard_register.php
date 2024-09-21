<?php
session_start();
require_once '../functions/activities.php';  // Incluir las funciones

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Variables para mensajes
$error = '';
$success = '';
$user_id = $_SESSION['user_id'];

// Procesar solicitudes de eliminación
if (isset($_GET['delete']) && isset($_GET['date']) && isset($_GET['hour'])) {
    $date = $_GET['date'];
    $hour = $_GET['hour'];

    // Llamar a la función deleteActivity
    $success = deleteActivity($date, $hour);
}

// Procesar el formulario de registro/actualización de actividades
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $activities = $_POST['activity'];
    $hours = $_POST['hour'];

    // Llamar a la función registerActivities
    $response = registerActivities($date, $activities, $hours, $user_id);

    // Mostrar el resultado
    if (strpos($response, 'duplicada') !== false) {
        $error = $response;
    } else {
        $success = $response;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Registrar actividades</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registrar actividades</h1>

        <form action="dashboard_register.php" method="POST">
            <label for="date">Selecciona una fecha (últimos 8 días, sin fines de semana):</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-8 days')); ?>" required>

            <!-- Mostrar mensaje si el día seleccionado es sábado o domingo -->
            <div id="mensajeFecha"></div>

            <!-- Tabla de actividades -->
            <h2>Actividades</h2>
            <table id="activitiesTable">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Actividad</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas de actividades existentes o nuevas -->
                </tbody>
            </table>
            <button type="button" id="agregarFila">Agregar actividad</button>

            <!-- Mostrar mensajes de error o éxito dentro del formulario -->
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success-message"><?php echo $success; ?></p>
            <?php endif; ?>

            <button type="submit">Guardar actividades</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activitiesTable = document.querySelector('#activitiesTable tbody');
            const agregarFilaBtn = document.querySelector('#agregarFila');
            const dateInput = document.querySelector('#date');
            const mensajeFecha = document.querySelector('#mensajeFecha');

            // Rango de horas permitidas (de 8am a 5pm)
            const horasDisponibles = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

            agregarFilaBtn.addEventListener('click', function () {
                const fila = document.createElement('tr');

                // Crear la celda para la hora (con un select)
                const celdaHora = document.createElement('td');
                const selectHora = document.createElement('select');
                selectHora.name = 'hour[]';
                horasDisponibles.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora;
                    option.textContent = hora;
                    selectHora.appendChild(option);
                });
                celdaHora.appendChild(selectHora);

                // Crear la celda para la actividad (con un input)
                const celdaActividad = document.createElement('td');
                const inputActividad = document.createElement('input');
                inputActividad.type = 'text';
                inputActividad.name = 'activity[]';
                inputActividad.required = true;
                celdaActividad.appendChild(inputActividad);

                // Crear la celda para eliminar (con un botón)
                const celdaEliminar = document.createElement('td');
                const botonEliminar = document.createElement('button');
                botonEliminar.type = 'button';
                botonEliminar.textContent = 'Eliminar';
                botonEliminar.classList.add('delete-btn');
                botonEliminar.addEventListener('click', function () {
                    fila.remove();
                });
                celdaEliminar.appendChild(botonEliminar);

                // Añadir las celdas a la fila
                fila.appendChild(celdaHora);
                fila.appendChild(celdaActividad);
                fila.appendChild(celdaEliminar);

                // Añadir la fila a la tabla
                activitiesTable.appendChild(fila);
            });
        });
    </script>
</body>
</html>
