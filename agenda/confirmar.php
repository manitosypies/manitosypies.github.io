<?php
require 'db.php';

date_default_timezone_set('America/Santiago'); // Ajusta a tu zona horaria si es necesario

$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');

$tipo = '';
$mensajeHTML = '';

// Validación básica
if (!$fecha || !$hora || !$nombre || !$email) {
  $tipo = 'error';
  $mensajeHTML = "<h2>❌ Datos incompletos</h2><p>Por favor completa todos los campos.</p>";
} else {
  // Validar formato fecha y hora
  if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    $tipo = 'error';
    $mensajeHTML = "<h2>❌ Fecha inválida</h2><p>Formato de fecha incorrecto.</p>";
  } elseif (!preg_match('/^\d{2}:\d{2}$/', $hora)) {
    $tipo = 'error';
    $mensajeHTML = "<h2>❌ Hora inválida</h2><p>Formato de hora incorrecto.</p>";
  } else {
    $now = new DateTime('now');
    $fecha_hora_seleccionada = DateTime::createFromFormat('Y-m-d H:i', $fecha . ' ' . $hora);

    if (!$fecha_hora_seleccionada) {
      $tipo = 'error';
      $mensajeHTML = "<h2>❌ Fecha y hora inválidas</h2><p>La fecha y hora proporcionadas no son válidas.</p>";
    } else {
      // Comparamos solo fechas
      $fecha_obj = DateTime::createFromFormat('Y-m-d', $fecha);
      $fecha_actual = new DateTime('today');

      // Si la fecha es anterior a hoy => error
      if ($fecha_obj < $fecha_actual) {
        $tipo = 'error';
        $mensajeHTML = "<h2>❌ Fecha pasada</h2><p>No se pueden reservar fechas anteriores al día de hoy.</p>";
      } 
      // Si la fecha es hoy, verificamos que la hora seleccionada sea mayor a la hora actual
      elseif ($fecha_obj == $fecha_actual) {
        if ($fecha_hora_seleccionada <= $now) {
          $tipo = 'error';
          $mensajeHTML = "<h2>❌ Hora pasada</h2><p>No se pueden reservar horas anteriores o iguales a la hora actual.</p>";
        }
      }

      // Si no hay error por fecha/hora seguimos con la validación en DB
      if ($tipo === '') {
        // Verificar si ya existe reserva para esa fecha y hora
        $sql_check = "SELECT COUNT(*) as count FROM reservas WHERE fecha = ? AND hora = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param('ss', $fecha, $hora);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row = $result_check->fetch_assoc();

        if ($row['count'] > 0) {
          $tipo = 'error';
          $mensajeHTML = "<h2>❌ Hora ocupada</h2><p>Ya existe una reserva para esa fecha y hora.</p>
            <a href='reservar.php?fecha=$fecha' class='btn-volver'>Volver a intentar</a>";
        } else {
          // Insertar reserva
          $sql = "INSERT INTO reservas (fecha, hora, nombre, email) VALUES (?, ?, ?, ?)";
          $stmt = $conexion->prepare($sql);
          $stmt->bind_param('ssss', $fecha, $hora, $nombre, $email);

          if ($stmt->execute()) {
            $tipo = 'exito';
            $mensajeHTML = "
              <h2>🎉 ¡Reserva confirmada!</h2>
              <p><strong>Fecha:</strong> $fecha</p>
              <p><strong>Hora:</strong> $hora</p>
              <p><strong>Nombre:</strong> " . htmlspecialchars($nombre) . "</p>
              <a href='index.html' class='btn-volver'>Volver al calendario</a>
            ";
          } else {
            $tipo = 'error';
            $mensajeHTML = "
              <h2>❌ Error al reservar</h2>
              <p>Ha ocurrido un error inesperado.</p>
              <a href='reservar.php?fecha=$fecha' class='btn-volver'>Volver a intentar</a>
            ";
          }
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Confirmación de Reserva</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffffff, #e09bf4);
      color: #333;
      padding: 40px 20px;
    }
    .notificacion {
      max-width: 500px;
      margin: 0 auto;
      padding: 25px;
      border-radius: 10px;
      text-align: center;
      font-size: 18px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }
    .notificacion.exito {
      background-color: #fce4ec;
      border-left: 6px solid #c2185b;
    }
    .notificacion.error {
      background-color: #ffe5e5;
      border-left: 6px solid #e53935;
      color: #b71c1c;
    }
    .btn-volver {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #c2185b;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn-volver:hover {
      background-color: #ad1457;
    }
  </style>
</head>
<body>

  <div class="notificacion <?= $tipo ?>">
    <?= $mensajeHTML ?>
  </div>

</body>
</html>
