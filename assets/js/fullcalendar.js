require('../css/fullcalendar.css');
require('../css/scheduler.css');
require('../css/scheduler.css');
require('../css/jquery.modal.min.css');
import $ from 'jquery';
import 'jquery-modal';
import 'fullcalendar';
import 'fullcalendar-scheduler';

const HOST = 'http://my-calendar.com'
var events = [];

const getEvents = fetch(HOST + '/api/events', {
  headers: {
    Accept: 'application/json'
  },
  credentials: 'same-origin'
}).then((response) => response.json()).then((json) => {
  events = json.events;
});
getEvents.then(showCalendar);

function showCalendar() {
  $('#calendar').fullCalendar({
    schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
    header: {
      center: 'month,agendaWeek'
    }, // buttons for switching between views
    themeSystem: 'bootstrap4',
    selectable: true,
    editable: true,
    selectHelper: true,
    select: function(start, end) {
      $('#start').val(start.format('YYYY-MM-DDTHH:mm:ss'))
      $('#end').val(end.format('YYYY-MM-DDTHH:mm:ss'))
      $('#ex1').modal()
      console.log(start.format('YYYY-MM-DD HH:mm:ss'))
      console.log(end.format('YYYY-MM-DD HH:mm:ss'))

    },

    events: events
  });
}

$('#event-form').submit(addEvent)

function addEvent(e) {
  e.preventDefault();
  const event = {
    'title': $('#title').val(),
    'start': $('#start').val(),
    'end': $('#end').val()
  };
  const calendar = $('#calendar').fullCalendar('getCalendar');

  console.log(event)
  fetch(HOST + '/api/events', {
    method: 'post',
    headers: {
      Accept: 'application/json',
      "Content-type": 'application/json'
    },
    credentials: 'same-origin',
    body: JSON.stringify(event)
  }).then((response) => response.json()).then((json) => {
    calendar.renderEvent(json)
  });
}
