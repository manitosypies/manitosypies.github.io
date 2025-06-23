<?php
require_once('../config/config.php');
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$images = glob(GALLERY_PATH . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<a href="logout.php" class="logout-btn">Cerrar sesión</a>
<div class="container">

<div class="container">
    <h2>Panel de Administración</h2>
   

    <h3>Subir nueva imagen</h3>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Subir</button>
    </form>

    <h3>Imágenes actuales</h3>
    <div class="gallery-admin">
        <?php foreach ($images as $img): ?>
            <div>
                <img src="<?= $img ?>" alt="">
                <form action="delete.php" method="post">
                    <input type="hidden" name="file" value="<?= basename($img) ?>">
                    <button type="submit" onclick="return confirm('¿Eliminar esta imagen?')">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

