$(document).ready(function () {
  const calendarEl = document.getElementById('calendar');

  // Inicializar FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      selectable: true,
      editable: true,

      // Cargar eventos desde el servidor
      events: function(fetchInfo, successCallback, failureCallback) {
          $.ajax({
              url: 'http://localhost/CodeIgniter/public/index.php/fetch-events',
              method: 'GET',
              data: {
                  start: fetchInfo.startStr,
                  end: fetchInfo.endStr
              },
              success: function(response) {
                  successCallback(JSON.parse(response));
              },
              error: function() {
                  failureCallback();
              }
          });
      },

      // Añadir evento
      select: function (info) {
          const title = prompt('Título del evento:');
          if (title) {
              calendar.addEvent({
                  title: title,
                  start: info.startStr,
                  end: info.endStr
              });
          }
      },

      // Eliminar evento
      eventClick: function (info) {
          if (confirm('¿Deseas eliminar este evento?')) {
              info.event.remove();
          }
      }
  });

  calendar.render();
});