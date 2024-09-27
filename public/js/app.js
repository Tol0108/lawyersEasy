import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    let calendarEl = document.getElementById('calendar');

    let calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',  // Vue initiale en grille (mois)
        selectable: true,  // Permettre la sélection de dates
        editable: true,    // Permettre de déplacer des événements
        events: '/api/disponibilites'  // Récupère les disponibilités via l'API
    });

    calendar.render();
});
