<?php
session_start();
require_once 'functions/users.php';  // Incluir funciones para usuarios
require_once 'controller/s3_controller.php';    // Incluir el controlador de S3

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
$profileImageUri = htmlspecialchars($userData['profile_image']); // Imagen de perfil actual

$message = ""; // Inicializa la variable para el mensaje

// Procesar el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profileImageUri = $userData['profile_image']; // Mantener la URI actual

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['tmp_name']) {
        $file = $_FILES['profile_image'];

        // Comprobar si hay errores en la subida del archivo
        if ($file['error'] !== UPLOAD_ERR_OK) {
            error_log('Error de subida: ' . print_r($file, true));
            echo "<script>alert('Error en el archivo subido.');</script>";
            return;
        }

        // Eliminar la imagen anterior de S3
        if ($profileImageUri) {
            deletePreviousProfileImageFromS3($username, $profileImageUri);
        }

        // Subir la nueva imagen a S3
        $response = uploadProfileImageToS3($username, $file);
        if ($response['success']) {
            $imageUrl = $response['url'];
            // Actualizar la base de datos con la nueva URL de la imagen
            $dbResponse = updateUserData($_SESSION['user_id'], $email, null, $imageUrl);
            if ($dbResponse) {
                echo "<script>alert('Imagen subida y URL guardada en la base de datos.');</script>";
            } else {
                echo "<script>alert('Imagen subida, pero hubo un problema al guardar en la base de datos.');</script>";
            }
        } else {
            echo "<script>alert('" . addslashes($response['message']) . "');</script>";
        }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" href="https://www.flaticon.es/iconos-gratis/lapiz" type="image/x-icon">
    
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
                <img src="<?php echo $profileImageUri ?: 'profile/profile.jpg'; ?>" alt="Foto de perfil" id="profileImage">
                <button onclick="openFileDialog()">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <input type="file" id="fileInput" name="profile_image" style="display: none;" onchange="updateProfileImage(this)">
            </div>

            <div class="profile-info">
                <p>Número de identificación: <strong><?php echo $idNumber; ?></strong></p>
                <p>Nombre de usuario: <strong><?php echo $username; ?></strong></p>
                <p>Email: <strong><?php echo $email; ?></strong></p>
                <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
                    <button type="submit">Guardar nueva foto de perfil</button>
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
