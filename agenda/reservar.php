<?php
require 'db.php';
date_default_timezone_set('America/Santiago'); // zona horaria
$fecha = $_GET['fecha'] ?? null;
if (!$fecha) {
  exit("Fecha no válida.");
}

// Horas predefinidas (formato HH:MM)
$horas = ["10:00", "11:00", "12:00", "13:00", "15:00", "16:00", "17:00", "18:00"];

// Consultar horas ocupadas para la fecha
$sql = "SELECT hora FROM reservas WHERE fecha = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param('s', $fecha);
$stmt->execute();
$resultado = $stmt->get_result();

// Normalizar horas ocupadas a formato HH:MM (sin segundos)
$ocupadas = [];
while ($fila = $resultado->fetch_assoc()) {
  $ocupadas[] = substr($fila['hora'], 0, 5);
}

$fecha_actual = date('Y-m-d');
$hora_actual = date('H:i');
$fecha_es_pasada = ($fecha < $fecha_actual);

// Contador de horas disponibles
$contador_horas_disponibles = 0;
foreach ($horas as $hora) {
  $bloqueada = false;

  if ($fecha_es_pasada) {
    $bloqueada = true;
  } elseif ($fecha === $fecha_actual) {
    $hora_sistema = new DateTime($hora);
    $hora_ahora = new DateTime($hora_actual);
    if ($hora_sistema <= $hora_ahora) {
      $bloqueada = true;
    }
  } elseif (in_array($hora, $ocupadas)) {
    $bloqueada = true;
  }

  if (!$bloqueada) $contador_horas_disponibles++;
}

$reserva_exito = isset($_GET['reserva']) && $_GET['reserva'] === 'exito';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Reserva de Hora</title>
  <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(to right, #ffffff, #e09bf4);
    margin: 0;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
    /* NAVBAR */
.navbar {
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
  padding: 0rem 3rem;
  background: linear-gradient(to right, #e8aff7, #d579ef);
  color: white;
  position: sticky;
  top: 0;
  z-index: 100;
  transition: top 0.3s ease-in-out; /* Agregar transición */
  margin-bottom: 2rem;
  border-bottom: 3px solid black; /* 🔥 Línea negra de separación */
}

.navbar.scrolled {
  background-color: #4a148c;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
  transition: background-color 0.3s ease, box-shadow 0.3s ease;
}


.navbar .logo {
  display: flex;
  align-items: center;
  gap: 10px;
}

.navbar .logo img {
  width: 90px; /* Tamaño del logo */
  transition: width 0.3s ease-in-out;
}

.navbar .logo span {
  font-size: 1.8rem; /* Tamaño del nombre */
  font-weight: bold;
  color: #fff;
  letter-spacing: 2px;
  transition: font-size 0.3s ease-in-out;
}

/* Enlaces de navegación */
.nav-links {
  list-style: none;
  display: flex;          /* Asegura que los enlaces estén en una fila horizontal */
  gap: 20px;              /* Espaciado entre los enlaces */
  margin: 0;              /* Elimina cualquier margen */
  padding: 0;             /* Elimina el padding por defecto */
}

/* Estilos para los enlaces */
.nav-links li a {
  text-decoration: none;
  color: white;
  font-size: 1.2rem;
  position: relative; /* Necesario para el pseudo-elemento */
  padding: 8px 12px; /* Añadimos algo de espacio para el borde */
  transition: color 0.3s ease, border 0.3s ease; /* Transición suave para color y borde */
  border: 2px solid transparent; /* Borde inicial invisible */
  border-radius: 20px; /* Bordes redondeados */
}


/* Efecto hover en los enlaces */
.nav-links li a:hover {
  color: #b2ebf2; /* Cambia el color al pasar el cursor */
  border-color: #b2ebf2; /* Añadimos el borde de color cuando el cursor pasa */
}

/* dropdown */

.navbar ul li.dropdown {
  position: relative;
}

.dropdown-menu {
  opacity: 0;
  transform: translateY(10px);
  visibility: hidden;
  position: absolute;
  top: 130%;
  left: 0;
  min-width: 190px;
  background-color: #bdb2ff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  z-index: 999;
  transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s;
  list-style: none;
}

.navbar ul li.dropdown:hover .dropdown-menu {
  display: block; /* por compatibilidad con tu estructura */
  opacity: 1;
  transform: translateY(0);
  visibility: visible;
}


.dropdown-menu li a {
  color: #ffffff;
  font-size: 1rem;
  padding: 10px 15px;
  text-decoration: none;
  display: block;
  transition: background 0.3s ease;
}

.dropdown-menu li a:hover {
  background-color: #e0e0e0;
  color: #000;
}

    .reserva-container {
      max-width: 500px;
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      animation: fadeIn 0.5s ease-in-out;
    }

    h2 {
      text-align: center;
      color: #c2185b;
      margin-bottom: 25px;
    }

    .formulario-reserva label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
      color: #444;
    }

    .formulario-reserva input[type="text"],
    .formulario-reserva input[type="email"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
      font-size: 16px;
    }

    .bloque-horas {
      margin: 20px 0;
    }

    .hora-opcion {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 12px;
      margin-bottom: 8px;
      border-radius: 6px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      transition: all 0.3s;
      cursor: pointer;
      user-select: none;
    }

    .hora-opcion input[type="radio"] {
      transform: scale(1.2);
    }

    .hora-opcion:hover:not(.ocupada) {
      background-color: #ffe3ed;
    }

    .hora-opcion.ocupada {
      background-color: #f8d7da;
      color: #721c24;
      text-decoration: line-through;
      cursor: not-allowed;
    }

    .hora-opcion.ocupada:hover {
      background-color: #f8d7da;
    }

    .formulario-reserva button {
      width: 100%;
      margin-top: 25px;
      padding: 12px;
      font-size: 16px;
      border: none;
      background-color: #c2185b;
      color: white;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .formulario-reserva button:hover {
      background-color: #ad1457;
    }

    .mensaje-bloqueo {
      background-color: #f8d7da;
      color: #721c24;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
      font-weight: bold;
      margin-top: 20px;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
   <!-- NAVBAR -->
   <nav class="navbar">
    <div class="logo">
        <img src="../img/logo.png" alt="Logo Eternal Beauty">
        <span>Manitos & Pies</span>
    </div>
    <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>
    <ul class="nav-links" id="nav-links">
        <li><a href="../index.php">Inicio</a></li>
        <li><a href="../index.php#services">Servicios</a></li>
            <li><a href="../index.php#promocion">Promociones</a></li>
            <li><a href="../ibdex.php#acordeon">Preguntas</a></li>
            <li><a href="../index.php#galeria">Galería</a></li>
        <!-- Dropdown -->
        <li class="dropdown">
        <a href="#">Más &#9662;</a> <!-- ▼ flechita -->
        <ul class="dropdown-menu">
            <li><a href="index.html">Agenda tu hora</a></li>
            <li><a href="../serviceone.php">Servicio 1</a></li>
            <li><a href="../servicetwo.php">Servicio 2</a></li>
            <li><a href="../servicethree.php">Servicio 3</a></li>
        </ul>
    </li>
        <li><a href="../index.php#footer">Contacto</a></li>
    </ul>
</nav>

  <div class="reserva-container">
  <?php
  $fecha_formateada = date('d-m-Y', strtotime($fecha));
  ?>
  <h2>Reserva para el <span><?= htmlspecialchars($fecha_formateada) ?></span></h2>

  <?php if ($fecha_es_pasada): ?>
    <div class="mensaje-bloqueo">
      No es posible reservar horas para días anteriores al día de hoy.
    </div>
  <?php elseif ($contador_horas_disponibles === 0): ?>
    <div class="mensaje-bloqueo">
      Todas las horas para este día están reservadas o no disponibles.
    </div>
  <?php else: ?>
    <form action="confirmar.php" method="POST" class="formulario-reserva" id="formReserva">
      <input type="hidden" name="fecha" value="<?= $fecha ?>">

      <div class="bloque-horas">
        <label>Selecciona hora:</label>
        <?php foreach ($horas as $hora):
          $bloqueada = false;

          if ($fecha_es_pasada) {
            $bloqueada = true;
          } elseif ($fecha === $fecha_actual) {
            $hora_sistema = new DateTime($hora);
            $hora_ahora = new DateTime($hora_actual);
            if ($hora_sistema <= $hora_ahora) {
              $bloqueada = true;
            }
          } elseif (in_array($hora, $ocupadas)) {
            $bloqueada = true;
          }
        ?>
          <label class="hora-opcion <?= $bloqueada ? 'ocupada' : '' ?>">
            <input type="radio" name="hora" value="<?= $hora ?>" <?= $bloqueada ? 'disabled' : 'required' ?>>
            <?= $hora ?> <?= $bloqueada ? '(No disponible)' : '' ?>
          </label>
        <?php endforeach; ?>
      </div>

      <label>Nombre:</label>
      <input type="text" name="nombre" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <button type="submit">Reservar</button>
    </form>
  <?php endif; ?>
  </div>

  <?php if ($reserva_exito): ?>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
      icon: 'success',
      title: 'Reserva Confirmada',
      text: 'Tu reserva se realizó con éxito.',
      confirmButtonColor: '#c2185b',
    });
  });
  </script>
  <?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const formulario = document.getElementById('formReserva');
  if (!formulario) return;

  formulario.addEventListener('submit', function (e) {
    e.preventDefault();

    const fechaRaw = formulario.querySelector('input[name="fecha"]').value;
    const fechaParts = fechaRaw.split('-');
    const fechaFormateada = `${fechaParts[2]}-${fechaParts[1]}-${fechaParts[0]}`;

    const horaSeleccionada = formulario.querySelector('input[name="hora"]:checked');
    const nombre = formulario.querySelector('input[name="nombre"]').value;

    if (!horaSeleccionada) {
      Swal.fire({
        icon: 'warning',
        title: 'Selecciona una hora',
        text: 'Debes elegir una hora disponible antes de reservar.'
      });
      return;
    }

    const hora = horaSeleccionada.value;

    Swal.fire({
      title: '¿Confirmar reserva?',
      html: `
        <p><strong>Nombre:</strong> ${nombre}</p>
        <p><strong>Fecha:</strong> ${fechaFormateada}</p>
        <p><strong>Hora:</strong> ${hora}</p>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#c2185b',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Sí, reservar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        formulario.submit();
      }
    });
  });
});
</script>
<script>
    // Esperar a que la página cargue completamente
window.addEventListener('load', () => {
    document.body.classList.add('page-load');
});

// Variables
let prevScrollPos = window.pageYOffset;  // Posición inicial del scroll
let navbar = document.querySelector('.navbar');
let scrollTimer;

// Función para manejar el desplazamiento
window.addEventListener('scroll', function() {
    let currentScrollPos = window.pageYOffset;

    // Si el usuario baja más de 100px, ocultamos el navbar
    if (prevScrollPos < currentScrollPos && currentScrollPos > 100) {
        navbar.style.top = "-80px"; // Oculta el navbar
    } else {
        navbar.style.top = "0"; // Muestra el navbar
    }

    prevScrollPos = currentScrollPos;

    // Si no hay desplazamiento durante un tiempo, mostrará el navbar
    clearTimeout(scrollTimer);
    scrollTimer = setTimeout(function() {
        navbar.style.top = "0"; // Después de cierto tiempo sin desplazarse, muestra el navbar
    }, 2000); // 2 segundos de espera sin movimiento
});
  </script>

</body>
</html>
