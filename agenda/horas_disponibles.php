<?php
require 'db.php';

$fecha = $_GET['fecha'] ?? null;
if (!$fecha) {
  exit("Fecha no válida.");
}

$horas = ["10:00", "11:00", "12:00", "13:00", "15:00", "16:00", "17:00", "18:00"];

$sql = "SELECT hora FROM reservas WHERE fecha = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $fecha);
$stmt->execute();
$resultado = $stmt->get_result();

$ocupadas = [];
while ($fila = $resultado->fetch_assoc()) {
  $ocupadas[] = $fila['hora'];
}

$hora_actual = date('H:i');
$fecha_actual = date('Y-m-d');
?>

<link rel="stylesheet" href="style.css">

<div class="reserva-container">
  <h2>Reserva para el <span><?= date('d-m-Y', strtotime($fecha)) ?></span></h2>

  <form action="confirmar.php" method="POST" class="formulario-reserva">
    <input type="hidden" name="fecha" value="<?= $fecha ?>">

    <div class="bloque-horas">
      <label>Selecciona hora:</label><br>
      <?php foreach ($horas as $hora):
        $deshabilitar = in_array($hora, $ocupadas);

        if ($fecha === $fecha_actual && $hora <= $hora_actual) {
          $deshabilitar = true;
        }
      ?>
        <label class="hora-opcion <?= $deshabilitar ? 'ocupada' : '' ?>">
          <input type="radio" name="hora" value="<?= $hora ?>" <?= $deshabilitar ? 'disabled' : 'required' ?>>
          <?= $hora ?> <?= $deshabilitar ? '(No disponible)' : '' ?>
        </label>
      <?php endforeach; ?>
    </div>

    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <button type="submit">Reservar</button>
  </form>
</div>
