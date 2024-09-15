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
if (isset($_GET['delete']) && isset($_GET['date']) && isset($_GET['hora'])) {
    $date = $_GET['date'];
    $hora = $_GET['hora'];

    $stmt = $conn->prepare("DELETE FROM activities WHERE date = ? AND hora = ?");
    $stmt->bind_param("ss", $date, $hora);
    if ($stmt->execute()) {
        $success = "Actividad eliminada correctamente.";
    } else {
        $error = "Error al eliminar la actividad.";
    }
}

// Procesar el formulario de agregar/actualizar activities
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];
    $activities = $_POST['actividad'];
    $horas = $_POST['hora'];

    // Validar si hay activities duplicadas (date y hora)
    $duplicados = [];

    foreach ($horas as $index => $hora) {
        $actividad = $activities[$index];

        // Consultar si ya existe una actividad registrada para la misma date y hora
        $stmt = $conn->prepare("SELECT * FROM activities WHERE date = ? AND hora = ?");
        $stmt->bind_param("ss", $date, $hora);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $duplicados[] = "Hora duplicada: $hora para la date $date";
        } else {
            // Insertar o actualizar la actividad
            $stmt = $conn->prepare("INSERT INTO activities (date, hora, actividad) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE actividad = ?");
            $stmt->bind_param("ssss", $date, $hora, $actividad, $actividad);
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
                        <th>Hora</th>
                        <th>Actividad</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas de activities existentes o nuevas -->
                </tbody>
            </table>
            <button type="button" id="agregarFila">Agregar Actividad</button>

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

            // Rango de horas permitidas (de 8am a 5pm)
            const horasDisponibles = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00'];

            agregarFilaBtn.addEventListener('click', function () {
                const fila = document.createElement('tr');

                // Crear la celda para la hora (con un select)
                const celdaHora = document.createElement('td');
                const selectHora = document.createElement('select');
                selectHora.name = 'hora[]';
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
                inputActividad.name = 'actividad[]';
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
