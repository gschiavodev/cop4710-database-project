window.addEventListener('load', function() 
{

    // When the start time is changed, set the minimum value of the end time to the start time
    document.getElementById('event_start_time').addEventListener('change', function() 
    {

        var start_time = this.value;
        var end_time_field = document.getElementById('event_end_time');
        end_time_field.min = start_time;

    });

});