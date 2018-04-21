require('../css/fullcalendar.css');
require('../css/scheduler.css');
import $ from 'jquery';
import 'fullcalendar';
import 'fullcalendar-scheduler';

$('#calendar').fullCalendar({
    header: { center: 'month,agendaWeek,timelineDay' }, // buttons for switching between views
    themeSystem: 'bootstrap4',
    events: [
      {
        id: '1',
        title  : 'event1',
        start  : '2018-04-21'
      },
      {
        id: '2',
        title  : 'event2',
        start  : '2018-04-11',
        end    : '2018-04-15'
      },
      {
        title  : 'event3',
        start  : '2018-04-21T18:30:00',
        allDay : false // will make the time show
      }
    ],
    resources: [
      {
        id: '1',
        title: 'Room A'
      },
      {
        id: '2',
        title: 'Room B'
      }
    ]
});
var calendar = $('#calendar').fullCalendar('getCalendar');

$(function() {
  calendar.on('dayClick', function(date, jsEvent, view) {
    calendar.changeView('timelineDay', date)
  });
});
