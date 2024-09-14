<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

include 'db_connection.php';
$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'empleado') {
    $activity = $_POST['activity'];
    $date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO activities (user_id, activity, date) VALUES ((SELECT id FROM users WHERE username = ?), ?, ?)");
    $stmt->bind_param("sss", $username, $activity, $date);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Empresa</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenido, <?php echo $username; ?></h2>

        <?php if ($role == 'empleado'): ?>
        <h3>Registrar Actividades Diarias</h3>
        <form action="dashboard.php" method="POST">
            <textarea name="activity" placeholder="Describe tu actividad de hoy..." required></textarea>
            <button type="submit">Registrar Actividad</button>
        </form>
        <?php endif; ?>

        <?php if ($role == 'jefe'): ?>
        <h3>Consultar Actividades Diarias</h3>
        <table>
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Actividad</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT u.username, a.activity, a.date FROM activities a JOIN users u ON a.user_id = u.id");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>{$row['username']}</td><td>{$row['activity']}</td><td>{$row['date']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php endif; ?>
        
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>
