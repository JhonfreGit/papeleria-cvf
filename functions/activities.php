<?php
require_once 'config_bd/db_connection.php';

// Función para eliminar actividades
function deleteActivity($date, $hour) {
    $pdo = getDBConnection(); // Usar PDO para la conexión

    $stmt = $pdo->prepare("DELETE FROM activities WHERE date = ? AND hour = ?");
    if ($stmt->execute([$date, $hour])) {
        return "Actividad eliminada correctamente.";
    } else {
        return "Error al eliminar la actividad.";
    }
}

// Función para registrar o actualizar actividades
function registerActivities($date, $activities, $hours, $user_id) {
    $pdo = getDBConnection(); // Usar PDO
    $duplicados = [];

    foreach ($hours as $index => $hour) {
        $activity = $activities[$index];

        // Verificar si ya existe una actividad con la misma fecha y hora
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE date = ? AND hour = ?");
        $stmt->execute([$date, $hour]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $duplicados[] = "Hora duplicada: $hour para la fecha $date";
        } else {
            // Insertar la nueva actividad
            $query = "INSERT INTO activities (user_id, activity, date, hour) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user_id, $activity, $date, $hour]);
        }
    }

    if (count($duplicados) > 0) {
        return implode('<br>', $duplicados);
    } else {
        return "Actividades registradas/actualizadas correctamente.";
    }
}

// Función para verificar si el usuario ha iniciado sesión y tiene el rol de jefe
function verificarSesionJefe() {
    session_start();
    if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'jefe') {
        header('Location: index.php');
        exit();
    }
}

// Función para obtener la lista de empleados
function obtenerEmpleados() {
    $pdo = getDBConnection(); // Usar PDO
    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = 'empleado'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener las actividades de un empleado seleccionado
function obtenerActividadesPorEmpleado($employee_id) {
    $pdo = getDBConnection(); // Usar PDO
    $stmt = $pdo->prepare("SELECT date, hour, activity FROM activities WHERE user_id = ? ORDER BY date ASC, hour ASC");
    $stmt->execute([$employee_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
