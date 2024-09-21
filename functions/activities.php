<?php
require_once '../config/db.php';

// Función para eliminar actividades
function deleteActivity($date, $hour) {
    $pdo = getDBConnection();

    $stmt = $pdo->prepare("DELETE FROM activities WHERE date = ? AND hour = ?");
    if ($stmt->execute([$date, $hour])) {
        return "Actividad eliminada correctamente.";
    } else {
        return "Error al eliminar la actividad.";
    }
}

// Función para registrar o actualizar actividades
function registerActivities($date, $activities, $hours, $user_id) {
    $pdo = getDBConnection();
    $duplicados = [];

    foreach ($hours as $index => $hour) {
        $activity = $activities[$index];

        // Verificar si ya existe una actividad con la misma fecha y hora
        $stmt = $pdo->prepare("SELECT * FROM activities WHERE date = ? AND hour = ?");
        $stmt->execute([$date, $hour]);
        $result = $stmt->fetchAll();

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
?>
