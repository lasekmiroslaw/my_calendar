require('../css/jquery.modal.min.css');
require('../css/fullcalendar.css');
require('../css/scheduler.css');
require('../css/app.css');
import $ from 'jquery';
import 'jquery-modal';
import 'fullcalendar';
import 'fullcalendar-scheduler';

const HOST = 'http://my-calendar.com'
var events = [];
const properties = ['title', 'start', 'end'];

const getEvents = fetch(HOST + '/api/events', {
  headers: {
    Accept: 'application/json'
  },
  credentials: 'same-origin'
}).then((response) => response.json()).then((json) => {
  events = json.events;
}).catch((err) => console.log(err));
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
    events: events,
    select: function(start, end) {
      $('#delete-btn').addClass('d-none');
      $('#start').val(start.format('YYYY-MM-DD[T]HH:mm'))
      $('#end').val(end.format('YYYY-MM-DD[T]HH:mm'))
      $('#title').val('')
      $('#modal-new-event').modal({
        showClose: false,
      });

      $('#event-form').off()
      $('#event-form').submit(addEvent)
    },
    eventClick: function(calEvent) {
      $('#delete-btn').removeClass('d-none');
      $('#event-form').off();
      $('#event-form').on('submit', {calEvent: calEvent}, editEvent);
      $('#delete-btn').off();
      $('#delete-btn').on('click', {id: calEvent.id}, deleteEvent);

      $('#modal-show-event').modal({
        showClose: false,
      });
      showPropertiesValue(calEvent)

      $('#edit-btn').click(function() {
        $('#start').val(calEvent.start.format('YYYY-MM-DD[T]HH:mm'));
        $('#title').val(calEvent.title);
        if(calEvent.end) $('#end').val(calEvent.end.format('YYYY-MM-DD[T]HH:mm'));

        $('#modal-new-event').modal({
            showClose: false,
        })
      });
    },
    eventDrop: function(event, delta, revertFunc) {
      moveEvent(event, revertFunc)
    }
  });
}

function addEvent(e) {
  e.preventDefault();
  const event = {
    'title': $('#title').val(),
    'start': $('#start').val(),
    'end': $('#end').val()
  };

  fetch(HOST + '/api/events', {
    method: 'post',
    headers: {
      Accept: 'application/json',
      "Content-type": 'application/json'
    },
    credentials: 'same-origin',
    body: JSON.stringify(event)
  }).then((response) => {
    if(response.status === 400 || response.status === 201) {
      return response.json();
    }
  })
    .then((json) => {
      if(json.message === 'Validation Failed') {
        showValidationErrors(json.errors.children)
      } else {
        const calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.renderEvent(json)

        $.modal.close();
      }
  }).catch((err) => {
      console.log(err)
  });
}

function showValidationErrors(properties) {
  for(let property in properties) {
    let object = (properties[property]);
    if(!object.hasOwnProperty('errors')) continue;

    $(`.error-${property}`).text(object['errors']);
  }
}

function showPropertiesValue(values) {
  properties.forEach((property) => {
    let propertyArr = [];
    propertyArr[property] = values[property];
    if (values[property] instanceof Object) {
      propertyArr[property] = values[property].calendar()
    }
      $(`.event-${property}`).text(propertyArr[property])
  })
}

$('#modal').on($.modal.AFTER_CLOSE, function() {
    properties.forEach((property) => {
      $(`.error-${property}`).text('');
    })
})

function moveEvent(calEvent, revertFunc) {
  const event = {
    'title': calEvent.title,
    'start': calEvent.start,
    'end': calEvent.end
  };
  fetch(HOST + '/api/events/' + calEvent.id, {
    method: 'put',
    headers: {
      Accept: 'application/json',
      "Content-type": 'application/json'
    },
    credentials: 'same-origin',
    body: JSON.stringify(event)
  }).then((response) => {
    if(response.status === 400 || response.status === 200) {
      return response.json();
    }
  })
    .then((json) => {
      if(json.message === 'Validation Failed') {
        revertFunc()

      }
      console.log(json)
  }).catch((err) => {
      console.log(err)
      revertFunc()
  });
}

function editEvent(e) {
  e.stopPropagation()
  const event = {
    'title': $('#title').val(),
    'start': $('#start').val(),
    'end': $('#end').val()
  };
  const calEvent = e.data.calEvent
  fetch(HOST + '/api/events/' + calEvent.id, {
    method: 'put',
    headers: {
      Accept: 'application/json',
      "Content-type": 'application/json'
    },
    credentials: 'same-origin',
    body: JSON.stringify(event)
  }).then((response) => {
    if(response.status === 400 || response.status === 200) {
      return response.json();
    }
  })
    .then((json) => {
      if(json.message === 'Validation Failed') {
        showValidationErrors(json.errors.children)
      } else {
        const calendar = $('#calendar').fullCalendar('getCalendar');
        calEvent.title = $('#title').val()
        calEvent.start = $.fullCalendar.moment($('#start').val())
        calEvent.end = $.fullCalendar.moment($('#end').val())
        calendar.updateEvent(calEvent)
        $.modal.close();
      }
  }).catch((err) => {
      console.log(err)
  });
}

function deleteEvent(e) {
  e.stopPropagation();
  const id = e.data.id
  fetch(HOST + '/api/events/' + id, {
    method: 'delete',
    headers: {
      Accept: 'application/json'
    },
    credentials: 'same-origin'
  }).then((response) =>  {
      if(response.status === 204) {
        const calendar = $('#calendar').fullCalendar('getCalendar');
        calendar.removeEvents(id)
        $.modal.close();
      }

  }).catch((err) => console.log(err));
}
