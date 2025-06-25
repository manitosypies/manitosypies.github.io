<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'db.php';
date_default_timezone_set('America/Santiago');

$id = $_GET['id'] ?? null;
if (!$id) {
    exit("ID de reserva no válido.");
}

// Obtener datos actuales de la reserva
$sql = "SELECT * FROM reservas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$reserva = $stmt->get_result()->fetch_assoc();

if (!$reserva) {
    exit("Reserva no encontrada.");
}

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $errores = [];

    if (!$fecha || !$hora || !$nombre || !$email) {
        $errores[] = "Todos los campos son obligatorios.";
    }

    // Validar si la nueva fecha y hora ya están ocupadas (excluyendo esta misma reserva)
    $sql_check = "SELECT COUNT(*) as total FROM reservas WHERE fecha = ? AND hora = ? AND id != ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param('ssi', $fecha, $hora, $id);
    $stmt_check->execute();
    $resultado = $stmt_check->get_result()->fetch_assoc();

    if ($resultado['total'] > 0) {
        $errores[] = "Ya existe otra reserva en esa fecha y hora.";
    }

    if (empty($errores)) {
        // Actualizar
        $sql_update = "UPDATE reservas SET fecha = ?, hora = ?, nombre = ?, email = ? WHERE id = ?";
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param('ssssi', $fecha, $hora, $nombre, $email, $id);

        if ($stmt_update->execute()) {
            header("Location: admin_panel.php");
            exit;
        } else {
            $errores[] = "Error al actualizar la reserva.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Reserva</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f7f3ff;
      padding: 40px;
      color: #333;
    }

    h1 {
      text-align: center;
      color: #5e35b1;
    }

    form {
      max-width: 500px;
      margin: 0 auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    button {
      margin-top: 20px;
      padding: 12px;
      width: 100%;
      background-color: #5e35b1;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #4527a0;
    }

    .errores {
      background-color: #ffe5e5;
      padding: 10px;
      margin-bottom: 15px;
      border-left: 4px solid #e53935;
      border-radius: 6px;
    }
  </style>
</head>
<body>

<h1>Editar Reserva</h1>

<form method="POST">
  <?php if (!empty($errores)): ?>
    <div class="errores">
      <?php foreach ($errores as $e): ?>
        <p>❌ <?= htmlspecialchars($e) ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <label for="fecha">Fecha:</label>
  <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($reserva['fecha']) ?>" required>

  <label for="hora">Hora:</label>
  <input type="time" name="hora" id="hora" value="<?= htmlspecialchars(substr($reserva['hora'], 0, 5)) ?>" required>

  <label for="nombre">Nombre:</label>
  <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($reserva['nombre']) ?>" required>

  <label for="email">Email:</label>
  <input type="email" name="email" id="email" value="<?= htmlspecialchars($reserva['email']) ?>" required>

  <button type="submit">Guardar cambios</button>
</form>

</body>
</html>
