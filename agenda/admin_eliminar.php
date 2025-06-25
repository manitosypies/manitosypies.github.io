<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';

// Obtener ID de la reserva
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    exit("ID no válido.");
}

// Eliminar la reserva
$sql = "DELETE FROM reservas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: admin_panel.php?eliminado=1");
    exit;
} else {
    echo "❌ Error al eliminar la reserva.";
}
?>
