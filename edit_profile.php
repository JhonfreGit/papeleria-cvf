<?php
session_start();
require_once 'functions/users.php';  // Incluir funciones para usuarios

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Obtén los datos del usuario
$userData = getUserData($_SESSION['user_id']);  // Obtener datos del usuario
$username = htmlspecialchars($userData['username']);
$idNumber = htmlspecialchars($userData['identification_number']);
$email = htmlspecialchars($userData['email']);

$message = ""; // Inicializa la variable para el mensaje

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Llamar a la función para actualizar los datos del usuario
    if (updateUserData($_SESSION['user_id'], $email, $password)) {
        $message = "<p class='success-message'>Datos actualizados correctamente.</p>";
    } else {
        $message = "<p class='error-message'>Error al actualizar los datos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Papelería CVF</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function openFileDialog() {
            document.getElementById('fileInput').click();
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo $username; ?></h1>
        
        <div class="profile-container">
            <div class="profile-pic">
                <img src="path/to/default/profile.jpg" alt="Foto de perfil" id="profileImage">
                <button onclick="openFileDialog()">✏️</button>
                <input type="file" id="fileInput" style="display: none;" onchange="updateProfileImage(this)">
            </div>

            <div class="profile-info">
                <p>Número de identificación: <strong><?php echo $idNumber; ?></strong></p>
                <p>Nombre de usuario: <strong><?php echo $username; ?></strong></p>
                <form action="edit_profile.php" method="POST">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo $email; ?>" required>

                    <label for="password">Contraseña:</label>
                    <input type="password" name="password" required>

                    <button type="submit">Guardar</button>
                </form>
                <?php if ($message): ?>
                    <div class="message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function updateProfileImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profileImage').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
