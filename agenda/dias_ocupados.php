<?php
require 'db.php';
date_default_timezone_set('America/Santiago');

$horas = ["10:00","11:00","12:00","13:00","15:00","16:00","17:00","18:00"];
$total = count($horas);
$hoy = date('Y-m-d');
$ahora = date('H:i');

// Obtenemos todas las reservas
$sql = "SELECT fecha, hora FROM reservas";
$res = $conexion->query($sql);

$reservas = [];
while ($r = $res->fetch_assoc()) {
    $f = date('Y-m-d', strtotime($r['fecha']));
    $h = substr($r['hora'], 0, 5);
    $reservas[$f][] = $h;
}

$dias_ocupados = [];

foreach ($reservas as $fecha => $horas_ocupadas) {
    if ($fecha !== $hoy) {
        // Si ya tiene tantas reservas como horas posibles
        if (count($horas_ocupadas) >= $total) {
            $dias_ocupados[] = $fecha;
        }
    } else {
        // Para hoy, considera sólo las horas que *queden disponibles*, es decir:
        $horas_futuras = array_filter($horas, fn($h) => $h > $ahora);
        $faltan = array_diff($horas_futuras, $horas_ocupadas);

        if (empty($faltan)) {
            $dias_ocupados[] = $fecha;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($dias_ocupados);
