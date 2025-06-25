<?php
require 'db.php';
session_start();

if (!isset($_SESSION['admin'])) {
  header('Location: admin_login.php');
  exit;
}

date_default_timezone_set('America/Santiago');

$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$por_pagina = 5; // Cantidad de días por página
$offset = ($pagina_actual - 1) * $por_pagina;

$filtro_fecha = isset($_GET['filtro_fecha']) ? $_GET['filtro_fecha'] : '';
$filtro_nombre = isset($_GET['filtro_nombre']) ? trim($_GET['filtro_nombre']) : '';

// Validar filtro fecha (YYYY-MM-DD)
if ($filtro_fecha && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filtro_fecha)) {
  $filtro_fecha = '';
}

// Construir condiciones para filtrar días
$params = [];
$types = '';
$where = [];

// Filtrar por fecha exacta (el día)
if ($filtro_fecha) {
  $where[] = 'fecha = ?';
  $params[] = $filtro_fecha;
  $types .= 's';
}

// Para filtro nombre, necesitamos días donde exista alguna reserva con ese nombre
// Esto implica una subconsulta o JOIN para filtrar días que tengan reserva(s) con ese nombre
if ($filtro_nombre) {
  $where[] = "id IN (
    SELECT DISTINCT fecha FROM reservas WHERE nombre LIKE ?
  )";
  // Pero fecha es string, y id es entero, no podemos hacer IN id = fecha
  // Mejor hacemos distinto: seleccionar fechas con reservas que coincidan en nombre
  
  // Por eso haremos una consulta para obtener fechas con reservas que coincidan en nombre:
  // y después filtrar sólo esas fechas
  
  // Entonces, la condición de WHERE la haremos luego en PHP
  
  // Por ahora solo tomamos el filtro de nombre para filtrar fechas en PHP.
}

// Construir consulta para obtener días únicos
// 1) Obtener todos los días con reservas que cumplan filtros (fecha y nombre)

$sqlDias = "SELECT fecha FROM reservas";
$whereFiltros = [];

if ($filtro_fecha) {
  $whereFiltros[] = "fecha = ?";
}
if ($filtro_nombre) {
  $whereFiltros[] = "nombre LIKE ?";
}

if (count($whereFiltros) > 0) {
  $sqlDias .= " WHERE " . implode(" AND ", $whereFiltros);
}

$sqlDias .= " GROUP BY fecha ORDER BY fecha DESC";

// Preparar y ejecutar
$stmtDias = $conexion->prepare($sqlDias);

if ($filtro_fecha && $filtro_nombre) {
  $paramNombre = "%$filtro_nombre%";
  $stmtDias->bind_param('ss', $filtro_fecha, $paramNombre);
} elseif ($filtro_fecha) {
  $stmtDias->bind_param('s', $filtro_fecha);
} elseif ($filtro_nombre) {
  $paramNombre = "%$filtro_nombre%";
  $stmtDias->bind_param('s', $paramNombre);
}

$stmtDias->execute();
$resultDias = $stmtDias->get_result();

$dias = [];
while ($fila = $resultDias->fetch_assoc()) {
  $dias[] = $fila['fecha'];
}

$total_dias = count($dias);
$total_paginas = ceil($total_dias / $por_pagina);

// Sacamos sólo las fechas para la página actual
$dias_pagina = array_slice($dias, $offset, $por_pagina);

// Obtener reservas por fecha para las fechas de esta página
$reservas_por_dia = [];
if (count($dias_pagina) > 0) {
  // Preparar placeholders para IN
  $placeholders = implode(',', array_fill(0, count($dias_pagina), '?'));

  $tipos = str_repeat('s', count($dias_pagina));
  $sqlReservas = "SELECT * FROM reservas WHERE fecha IN ($placeholders) ORDER BY hora ASC";

  $stmtReservas = $conexion->prepare($sqlReservas);

  // Bind params dinámicamente
  $stmtReservas->bind_param($tipos, ...$dias_pagina);

  $stmtReservas->execute();
  $resultReservas = $stmtReservas->get_result();

  while ($r = $resultReservas->fetch_assoc()) {
    $reservas_por_dia[$r['fecha']][] = $r;
  }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel de Administración - Reservas por día</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;700&display=swap" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #ffffff, #e09bf4);
      color: #333;
      padding: 40px 20px;
    }

    .container {
      max-width: 960px;
      margin: 0 auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #c2185b;
      margin-bottom: 30px;
    }

    form#filtros {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    form#filtros input[type="date"],
    form#filtros input[type="text"] {
      padding: 8px 12px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      min-width: 200px;
    }

    form#filtros button {
      background-color: #7b1fa2;
      border: none;
      color: white;
      padding: 10px 18px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    form#filtros button:hover {
      background-color: #6a1b9a;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    thead {
      background-color: #c2185b;
      color: white;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: top;
    }

    tr:hover {
      background-color: #fce4ec;
    }

    .btn {
      display: inline-block;
      padding: 6px 12px;
      font-size: 14px;
      border-radius: 6px;
      text-decoration: none;
      transition: background-color 0.3s;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #7b1fa2;
      color: white;
    }

    .btn-primary:hover {
      background-color: #6a1b9a;
    }

    .btn-danger {
      background-color: #e53935;
      color: white;
    }

    .btn-danger:hover {
      background-color: #c62828;
    }

    .btn-secondary {
      background-color: #9e9e9e;
      color: white;
      margin-bottom: 20px;
    }

    .btn-secondary:hover {
      background-color: #757575;
    }

    .no-reservas {
      background-color: #fff3cd;
      padding: 15px;
      border: 1px solid #ffeeba;
      border-radius: 8px;
      color: #856404;
      font-weight: bold;
      text-align: center;
    }

    .paginacion {
      text-align: center;
      margin-top: 10px;
    }

    .paginacion button {
      background-color: #7b1fa2;
      border: none;
      color: white;
      padding: 8px 14px;
      margin: 0 5px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }

    .paginacion button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
      color: #666;
    }

    .paginacion button:hover:not(:disabled) {
      background-color: #6a1b9a;
    }

    ul.horas-lista {
      list-style: none;
      padding-left: 0;
      margin: 0;
    }

    ul.horas-lista li {
      background-color: #f3e5f5;
      margin: 4px 0;
      padding: 6px 10px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
    }

  </style>
</head>
<body>

  <div class="container">
    <h2>📋 Panel de Administración - Reservas por Día</h2>

    <a href="admin_logout.php" class="btn btn-secondary">Cerrar sesión</a>

    <form id="filtros" method="GET" action="admin_panel.php">
      <input type="date" name="filtro_fecha" value="<?= htmlspecialchars($filtro_fecha) ?>" placeholder="Filtrar por fecha" />
      <input type="text" name="filtro_nombre" value="<?= htmlspecialchars($filtro_nombre) ?>" placeholder="Filtrar por nombre" />
      <button type="submit">Filtrar</button>
    </form>

    <?php if ($total_dias === 0): ?>
      <div class="no-reservas">No se encontraron reservas con esos filtros.</div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Horas reservadas</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($dias_pagina as $fecha): ?>
            <tr>
              <td><?= htmlspecialchars($fecha) ?></td>
              <td>
                <ul class="horas-lista">
                  <?php if (isset($reservas_por_dia[$fecha])): ?>
                    <?php foreach ($reservas_por_dia[$fecha] as $reserva): ?>
                      <li>
                        <span><?= substr($reserva['hora'], 0, 5) ?></span>
                        <span> - <?= htmlspecialchars($reserva['nombre']) ?> (<?= htmlspecialchars($reserva['email']) ?>)</span>
                        <span>
                          <a href="admin_editar.php?id=<?= $reserva['id'] ?>" class="btn btn-primary" style="padding:4px 8px; font-size:12px;">Editar</a>
                          <a href="admin_eliminar.php?id=<?= $reserva['id'] ?>" class="btn btn-danger eliminar-btn" style="padding:4px 8px; font-size:12px;">Eliminar</a>
                        </span>
                      </li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li>No hay reservas</li>
                  <?php endif; ?>
                </ul>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="paginacion">
        <form id="paginacionForm" method="GET" style="display:inline-block;">
          <!-- Mantener filtros en paginacion -->
          <input type="hidden" name="filtro_fecha" value="<?= htmlspecialchars($filtro_fecha) ?>" />
          <input type="hidden" name="filtro_nombre" value="<?= htmlspecialchars($filtro_nombre) ?>" />
          <button type="submit" name="pagina" value="<?= max(1, $pagina_actual - 1) ?>" <?= $pagina_actual <= 1 ? 'disabled' : '' ?>>← Anterior</button>
          <span>Página <?= $pagina_actual ?> de <?= $total_paginas ?></span>
          <button type="submit" name="pagina" value="<?= min($total_paginas, $pagina_actual + 1) ?>" <?= $pagina_actual >= $total_paginas ? 'disabled' : '' ?>>Siguiente →</button>
        </form>
      </div>
    <?php endif; ?>
  </div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Confirmación SweetAlert2 para eliminar
    const botonesEliminar = document.querySelectorAll('.eliminar-btn');

    botonesEliminar.forEach(boton => {
      boton.addEventListener('click', function (e) {
        e.preventDefault();
        const enlace = this.getAttribute('href');

        Swal.fire({
          title: '¿Eliminar reserva?',
          text: "Esta acción no se puede deshacer.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#9e9e9e',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = enlace;
          }
        });
      });
    });
  });
</script>

</body>
</html>
