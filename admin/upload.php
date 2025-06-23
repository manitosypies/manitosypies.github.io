<?php
require_once('../config/config.php');

// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar sesión
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Comprobar que se envió un archivo
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $fileName = basename($_FILES['image']['name']);
    $fileTmp = $_FILES['image']['tmp_name'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validar extensión
    if (in_array($ext, $allowed)) {

        // Validar que realmente es una imagen
        $check = getimagesize($fileTmp);
        if ($check !== false) {

            // Crear nombre único
            $newName = time() . '_' . preg_replace("/[^a-zA-Z0-9\._-]/", "", $fileName);
            $destination = GALLERY_PATH . $newName;

            // Subir archivo
            if (move_uploaded_file($fileTmp, $destination)) {
                header("Location: dashboard.php");
                exit;
            } else {
                echo "❌ Error al mover el archivo a la carpeta de destino.";
            }

        } else {
            echo "❌ El archivo no es una imagen válida.";
        }

    } else {
        echo "❌ Formato no permitido. Usa JPG, PNG o GIF.";
    }

} else {
    echo "❌ No se ha enviado ningún archivo o hubo un error al subirlo.";
}
?>
<a href="dashboard.php">← Volver al panel</a>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
</head>
<body>
    <h2>Bienvenido al panel de administración</h2>
    <a href="logout.php">Cerrar sesión</a>
    
    <h3>Subir nueva imagen</h3>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Subir</button>
    </form>

    <h3>Imágenes en la galería</h3>
    <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <?php foreach ($images as $img): ?>
            <div style="text-align:center;">
                <img src="<?= $img ?>" width="150"><br>
                <form action="delete.php" method="post">
                    <input type="hidden" name="file" value="<?= basename($img) ?>">
                    <button type="submit" onclick="return confirm('¿Eliminar esta imagen?')">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
