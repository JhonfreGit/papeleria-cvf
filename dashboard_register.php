<?php
session_start();
include 'db_connection.php';  // Incluir la conexión a la base de datos

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Variables para almacenar mensajes de error o éxito
$error = '';
$success = '';

// Procesar solicitudes de eliminación
if (isset($_GET['delete']) && isset($_GET['date']) && isset($_GET['hour'])) {
    $date = $_GET['date'];
    $hour = $_GET['hour'];

    $stmt = $conn->prepare("DELETE FROM activities WHERE date = ? AND hour = ?");
    $stmt->bind_param("ss", $date, $hour);
    if ($stmt->execute()) {
        $success = "activity eliminada correctamente.";
    } else {
        $error = "Error al eliminar la activity.";
    }
}

// Procesar el formulario de agregar/actualizar activities
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $activities = $_POST['activity'];
    $hours = $_POST['hour'];
    $userId = $_SESSION['id'];

    // Validar si hay activities duplicadas (date y hour)
    $duplicados = [];

    foreach ($hours as $index => $hour) {
        $activity = $activities[$index];

        // Consultar si ya existe una activity registrada para la misma date y hour
        $stmt = $conn->prepare("SELECT * FROM activities WHERE date = ? AND hour = ?");
        $stmt->bind_param("ss", $date, $hour);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $duplicados[] = "hour duplicada: $hour para la date $date";
        } else {
            // Insertar o actualizar la activity
            $stmt = $conn->prepare("INSERT INTO activities (date, hour, activity, user_id) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE activity = ?");
            $stmt->bind_param("ssss", $date, $hour, $activity, $userId);
            $stmt->execute();
        }
    }

    if (count($duplicados) > 0) {
        $error = implode('<br>', $duplicados);
    } else {
        $success = "activities registradas/actualizadas correctamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Registrar activities</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registrar activities</h1>

        <form action="dashboard.php" method="POST">
            <label for="date">Selecciona una date (últimos 8 días, sin fines de semana):</label>
            <input type="date" id="date" name="date" max="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d', strtotime('-8 days')); ?>" required>

            <!-- Mostrar mensaje si el día seleccionado es sábado o domingo -->
            <div id="mensajedate"></div>

            <!-- Tabla de activities -->
            <h2>activities</h2>
            <table id="activitiesTable">
                <thead>
                    <tr>
                        <th>hour</th>
                        <th>activity</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas de activities existentes o nuevas -->
                </tbody>
            </table>
            <button type="button" id="agregarFila">Agregar activity</button>

            <!-- Mostrar mensajes de error o éxito -->
            <?php if ($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success-message"><?php echo $success; ?></p>
            <?php endif; ?>

            <button type="submit">Guardar activities</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const activitiesTable = document.querySelector('#activitiesTable tbody');
            const agregarFilaBtn = document.querySelector('#agregarFila');
            const dateInput = document.querySelector('#date');
            const mensajedate = document.querySelector('#mensajedate');

            // Rango de hours permitidas (de 8am a 5pm)
            const hoursDisponibles = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

            agregarFilaBtn.addEventListener('click', function () {
                const fila = document.createElement('tr');

                // Crear la celda para la hour (con un select)
                const celdahour = document.createElement('td');
                const selecthour = document.createElement('select');
                selecthour.name = 'hour[]';
                hoursDisponibles.forEach(hour => {
                    const option = document.createElement('option');
                    option.value = hour;
                    option.textContent = hour;
                    selecthour.appendChild(option);
                });
                celdahour.appendChild(selecthour);

                // Crear la celda para la activity (con un input)
                const celdaactivity = document.createElement('td');
                const inputactivity = document.createElement('input');
                inputactivity.type = 'text';
                inputactivity.name = 'activity[]';
                inputactivity.required = true;
                celdaactivity.appendChild(inputactivity);

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
                fila.appendChild(celdahour);
                fila.appendChild(celdaactivity);
                fila.appendChild(celdaEliminar);

                // Añadir la fila a la tabla
                activitiesTable.appendChild(fila);
            });
        });
    </script>
</body>
</html>
