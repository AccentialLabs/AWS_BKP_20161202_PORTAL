<link rel='stylesheet' href='fullcalendar.css' />
<script src='lib/jquery.min.js'></script>
<script src='lib/moment.min.js'></script>
<script src='fullcalendar.js'></script>

<script>
	$(document).ready(function() {

	
	$('#calendar').fullCalendar({
    events: [
        {
            title: 'My Event',
            start: '2016-10-10',
            url: 'http://google.com/'
        },
		{
            title: 'My Event',
            start: '2016-10-11',
            url: 'http://google.com/'
        }
        // other events here
    ],
    eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
    }	
	  
});

});
</script>

<div id='calendar' ></div>