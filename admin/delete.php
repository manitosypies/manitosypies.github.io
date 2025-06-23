<?php
require_once('../config/config.php');

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['file'])) {
    $file = basename($_POST['file']);
    $filepath = GALLERY_PATH . $file;

    if (file_exists($filepath)) {
        unlink($filepath);
    }
}

header("Location: dashboard.php");
exit;
?>
