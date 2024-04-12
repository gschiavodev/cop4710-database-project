
async function init_map() 
{
    // Request needed libraries.
    //@ts-ignore
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    // The map, centered at the event location
    const map = new Map(document.getElementById("map"), 
    {
        zoom: 15,
        center: { lat: -34.397, lng: 150.644 }, // Default coordinates
        mapId: window.google_api_key,
        streetViewControl: false,  // Disable Street View control
        mapTypeControlOptions: 
        {   
            // Customize Map Type control options
            mapTypeIds: ['roadmap', 'satellite']
        }
    });

    // Use the Geocoding API to get the latitude and longitude from the address
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': window.address }, function(results, status) 
    {
        
        if (status == 'OK') 
        {
            // The marker, positioned at the event location
            const marker = new AdvancedMarkerElement({
                map: map,
                position: results[0].geometry.location,
                title: "Event Location",
            });

            // Center the map at the event location
            map.setCenter(results[0].geometry.location);
        } 
        else 
        {
            alert('Geocode was not successful for the following reason: ' + status);
        }

    });

}

window.addEventListener('load', function()
{

    // Check if the Google API key and address are set
    if (!window.google_api_key || !window.address) return;

    // Load the Google Maps API
    var script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${window.google_api_key}&callback=init_map&loading=async`;
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);
    
});