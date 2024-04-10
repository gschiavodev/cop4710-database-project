function init_auto_complete() 
{

    // Create the autocomplete object, restricting the search to geographical location types.
    var input = document.getElementById('address_line_01');
    var auto_complete = new google.maps.places.Autocomplete(input);

    // Set the data fields to return when the user selects a place.
    auto_complete.setFields(['address_components', 'geometry']);

    // Mapping of state names to abbreviations
    var state_mapping = 
    {
        'Alabama': 'AL',
        'Alaska': 'AK',
        'Arizona': 'AZ',
        'Arkansas': 'AR',
        'California': 'CA',
        'Colorado': 'CO',
        'Connecticut': 'CT',
        'Delaware': 'DE',
        'District Of Columbia': 'DC',
        'Florida': 'FL',
        'Georgia': 'GA',
        'Hawaii': 'HI',
        'Idaho': 'ID',
        'Illinois': 'IL',
        'Indiana': 'IN',
        'Iowa': 'IA',
        'Kansas': 'KS',
        'Kentucky': 'KY',
        'Louisiana': 'LA',
        'Maine': 'ME',
        'Maryland': 'MD',
        'Massachusetts': 'MA',
        'Michigan': 'MI',
        'Minnesota': 'MN',
        'Mississippi': 'MS',
        'Missouri': 'MO',
        'Montana': 'MT',
        'Nebraska': 'NE',
        'Nevada': 'NV',
        'New Hampshire': 'NH',
        'New Jersey': 'NJ',
        'New Mexico': 'NM',
        'New York': 'NY',
        'North Carolina': 'NC',
        'North Dakota': 'ND',
        'Ohio': 'OH',
        'Oklahoma': 'OK',
        'Oregon': 'OR',
        'Pennsylvania': 'PA',
        'Rhode Island': 'RI',
        'South Carolina': 'SC',
        'South Dakota': 'SD',
        'Tennessee': 'TN',
        'Texas': 'TX',
        'Utah': 'UT',
        'Vermont': 'VT',
        'Virginia': 'VA',
        'Washington': 'WA',
        'West Virginia': 'WV',
        'Wisconsin': 'WI',
        'Wyoming': 'WY'
    };

    auto_complete.addListener('place_changed', function() 
    {

        // Get the place details from the autocomplete object.
        var place = auto_complete.getPlace();

        // Check if the place has geometry
        if (!place.geometry) 
        {

            // User entered the name of a Place that was not suggested and pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
            
        }

        // Reset the fields
        document.getElementById('address_line_01').value = '';
        document.getElementById('address_line_02').value = '';
        document.getElementById('city').value = '';
        document.getElementById('zip_code').value = '';

        // Reset the state dropdown
        var state_select = document.getElementById('state');
        if (state_select) state_select.selectedIndex = 0;

        // Variables to store the street number and route
        var street_number = '';
        var route = '';

        // Parse the address components and fill in the corresponding fields
        var address_components = place.address_components;
        for (var i = 0; i < address_components.length; i++) 
        {

            // Get the address type and value
            var address_type = address_components[i].types[0];
            var val = address_components[i].long_name;

            // Fill in the corresponding field
            switch(address_type) 
            {

                case 'street_number':
                {
                    street_number = val;
                    break;
                }

                case 'route':
                {
                    route = val;
                    break;
                }

                case 'locality':
                {
                    document.getElementById('city').value = val;
                    break;
                }

                case 'administrative_area_level_1':
                {

                    // Select the state from the dropdown list
                    if (state_select) 
                    {

                        // Get the state abbreviation
                        var state_abbreviation = state_mapping[val];

                        // Set the selected index
                        for (var j = 0; j < state_select.options.length; j++) 
                        {
                            if (state_select.options[j].value == state_abbreviation) 
                            {
                                state_select.selectedIndex = j;
                                break;
                            }
                        }
                    }

                    break;

                }

                case 'postal_code':
                {

                    document.getElementById('zip_code').value = val;
                    break;

                }

            }

        }

        // Combine street number and route and set as address_line_01 value
        document.getElementById('address_line_01').value = street_number + ' ' + route;

    });
}

window.onload = function() 
{

    // Check if the Google API key is set
    if (!window.google_api_key) return;

    // Load the Google Maps API
    var script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${window.google_api_key}&libraries=places&callback=init_auto_complete&loading=async`;
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);
};
