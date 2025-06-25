document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
  
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      dateClick: function(info) {
        const fecha = info.dateStr;
  
        // Cargar horas disponibles por AJAX
        $.post('horas_disponibles.php', { fecha: fecha }, function(data) {
            console.log("Respuesta del servidor:", data);
            $('#horas-container').html(`<h3>Selecciona una hora para el ${fecha}:</h3>` + data);
            $('#formulario-container').html('');
          }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en AJAX:", textStatus, errorThrown);
          });
          
      }
    });
  
    calendar.render();
  });
  
  // Esta función se llama cuando se selecciona una hora
  function mostrarFormulario(fecha, hora) {
    $('#formulario-container').html(`
      <h3>Reserva para ${fecha} a las ${hora}</h3>
      <form action="reservar.php" method="POST">
        <input type="hidden" name="fecha" value="${fecha}">
        <input type="hidden" name="hora" value="${hora}">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Confirmar Reserva</button>
      </form>
    `);
  }

  
  
  