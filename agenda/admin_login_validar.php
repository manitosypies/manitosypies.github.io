<?php
session_start();
require 'db.php';

$usuario = $_POST['usuario'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

$sql = "SELECT * FROM administradores WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($admin = $result->fetch_assoc()) {
  if (password_verify($contrasena, $admin['contrasena'])) {
    $_SESSION['admin'] = $admin['usuario'];
    header("Location: admin_panel.php");
    exit;
  }
}

echo "<p style='color:red;'>Acceso denegado. Usuario o contraseña incorrectos.</p><a href='admin_login.php'>Volver</a>";
