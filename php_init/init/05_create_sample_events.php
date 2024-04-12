<?php

    // Include the necessary php files
    include_once "/var/www/html/php/location.php";
    include_once "/var/www/html/php/event.php";
    include_once "/var/www/html/php/private_event.php";
    include_once "/var/www/html/php/public_event.php";
    include_once "/var/www/html/php/rso_event.php";
    include_once "/var/www/html/php/user.php";
    include_once "/var/www/html/php/rso.php";

    function create_sample_public_events()
    {

        // Get the public events from the public_events.json file
        $json_public_events = file_get_contents("./init/json/events/public_events.json");
        $public_events = json_decode($json_public_events, true);

        // Loop through the public events
        foreach ($public_events as $public_event)
        {

            // Get the event information
            $event_name = $public_event["name"];
            $event_description = $public_event["description"];
            $event_category = $public_event["category"];
            $event_email = $public_event["email"];
            $event_phone_number = $public_event["phone_number"];
            $event_date = $public_event["date"];
            $event_start_time = $public_event["start_time"];
            $event_end_time = $public_event["end_time"];
            $location = $public_event["location"];

            // Get the location information
            $location_name = $location["name"];
            $location_address_line_01 = $location["address_line_01"];
            $location_address_line_02 = $location["address_line_02"];
            $location_city = $location["city"];
            $location_state = $location["state"];
            $location_zip_code = $location["zip_code"];

            // Try to get the location by the full address
            $location = get_location_by_full_address($location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

            // Check if the location exists
            if (!$location)
            {

                // Create the location
                $location_id = create_location($location_name, $location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

                // Check if create_location returned an string
                if (is_string($location_id)) 
                {

                    // Print the error message
                    echo "CREATE_SAMPLE_EVENT: Failed to create location with name " . $location_name . "\n\n";
                    exit(1);

                }

                // Check if the location was created
                if (!$location_id)
                {

                    // Print an error message and exit
                    echo "CREATE_SAMPLE_EVENT: Could not create public event location with name " . $location_name . "\n\n";
                    exit(1);

                }

            }
            else
            {

                // Get the location ID
                $location_id = $location["id"];

            }

            // Create the event
            $event_id = create_event($event_name, $event_description, $event_category, $event_email, $event_phone_number, $event_date, $event_start_time, $event_end_time, $location_id);

            // Check if the event failed to be created
            if (!$event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not create public event with name " . $event_name . "\n\n";
                exit(1);

            }

            // Create the public event
            $public_event_id = create_public_event_by_event_id($event_id);

            // Check if the public event failed to be created
            if (!$public_event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not add event to public events with name " . $event_name . "\n\n";
                exit(1);

            }

            // Success message
            echo "CREATE_SAMPLE_EVENT: Created public event with name " . $event_name . "\n\n";

        }

    }

    function create_sample_private_events()
    {

        // Get the private events from the private_events.json file
        $json_private_events = file_get_contents("./init/json/events/private_events.json");
        $private_events = json_decode($json_private_events, true);

        // Loop through the private events
        foreach ($private_events as $private_event)
        {

            // Get the email of the rso admin of the private event
            $rso_admin_email = $private_event["rso_admin_email"];

            // Get the event information
            $event_name = $private_event["name"];
            $event_description = $private_event["description"];
            $event_category = $private_event["category"];
            $event_phone_number = $private_event["phone_number"];
            $event_date = $private_event["date"];
            $event_start_time = $private_event["start_time"];
            $event_end_time = $private_event["end_time"];
            $location = $private_event["location"];

            // Get the location information
            $location_name = $location["name"];
            $location_address_line_01 = $location["address_line_01"];
            $location_address_line_02 = $location["address_line_02"];
            $location_city = $location["city"];
            $location_state = $location["state"];
            $location_zip_code = $location["zip_code"];

            // Get the RSO's admin ID from the RSO's admin email
            $rso_id = get_user_id_by_university_email($rso_admin_email);

            // Check if the RSO's admin ID was found
            if (!$rso_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not find RSO's admin_id with admin's email " . $rso_admin_email . "\n\n";
                exit(1);

            }

            // Get the RSO from the RSO's admin ID (assume only one RSO per admin)
            $rsos = get_rsos_by_admin_id($rso_id);

            // Check if there are any RSOs
            if ($rsos->num_rows == 0)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not find any RSOs with admin_id " . $rso_id . "\n\n";
                exit(1);

            }

            // Get the RSO ID
            $rso_id = $rsos->fetch_assoc()["id"];

            // Try to get the location by the full address
            $location = get_location_by_full_address($location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

            // Check if the location exists
            if (!$location)
            {

                // Create the location
                $location_id = create_location($location_name, $location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

                // Check if create_location returned an string
                if (is_string($location_id)) 
                {

                    // Print the error message
                    echo "CREATE_SAMPLE_EVENT: Failed to create private event location with name " . $location_name . "\n\n";
                    exit(1);

                }

                // Check if the location failed to be created
                if (!$location_id)
                {

                    // Print an error message and exit
                    echo "CREATE_SAMPLE_EVENT: Could not create private event location with name " . $location_name . "\n\n";
                    exit(1);

                }

            }
            else
            {

                // Get the location ID
                $location_id = $location["id"];

            }

            // Create the event
            $event_id = create_event($event_name, $event_description, $event_category, $rso_admin_email, $event_phone_number, $event_date, $event_start_time, $event_end_time, $location_id);

            // Check if the event failed to be created
            if (!$event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not create private event with name " . $event_name . "\n\n";
                exit(1);

            }

            // Create the private event
            $private_event_id = create_private_event_by_event_and_rso_id($event_id, $rso_id);

            // Check if the private event failed to be created
            if (!$private_event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not add event to private events with name " . $event_name . "\n\n";
                exit(1);

            }

            // Success message
            echo "CREATE_SAMPLE_EVENT: Created private event with name " . $event_name . "\n\n";

        }

    }

    function create_sample_rso_events()
    {

        // Get the RSO events from the rso_events.json file
        $json_rso_events = file_get_contents("./init/json/events/rso_events.json");
        $rso_events = json_decode($json_rso_events, true);

        // Loop through the RSO events
        foreach ($rso_events as $rso_event)
        {

            // Get the RSO's admin email
            $rso_admin_email = $rso_event["rso_admin_email"];

            // Get the event information
            $event_name = $rso_event["name"];
            $event_description = $rso_event["description"];
            $event_category = $rso_event["category"];
            $event_phone_number = $rso_event["phone_number"];
            $event_date = $rso_event["date"];
            $event_start_time = $rso_event["start_time"];
            $event_end_time = $rso_event["end_time"];
            $location = $rso_event["location"];

            // Get the location information
            $location_name = $location["name"];
            $location_address_line_01 = $location["address_line_01"];
            $location_address_line_02 = $location["address_line_02"];
            $location_city = $location["city"];
            $location_state = $location["state"];
            $location_zip_code = $location["zip_code"];

            // Get the RSO's admin ID from the RSO's admin email
            $rso_id = get_user_id_by_university_email($rso_admin_email);

            // Check if the RSO's admin ID was found
            if (!$rso_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not find RSO's admin_id with admin's email " . $rso_admin_email . "\n\n";
                exit(1);

            }

            // Get the RSO from the RSO's admin ID (assume only one RSO per admin)
            $rsos = get_rsos_by_admin_id($rso_id);

            // Check if there are any RSOs
            if ($rsos->num_rows == 0)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not find any RSOs with admin_id " . $rso_id . "\n\n";
                exit(1);

            }

            // Get the RSO ID
            $rso_id = $rsos->fetch_assoc()['id'];

            // Try to get the location by the full address
            $location = get_location_by_full_address($location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

            // Check if the location exists
            if (!$location)
            {

                // Create the location
                $location_id = create_location($location_name, $location_address_line_01, $location_address_line_02, $location_city, $location_state, $location_zip_code);

                // Check if create_location returned an string
                if (is_string($location_id)) 
                {

                    // Print the error message
                    echo "CREATE_SAMPLE_EVENT: Failed to create RSO event location with name " . $location_name . "\n\n";
                    exit(1);

                }

                // Check if the location failed to be created
                if (!$location_id)
                {

                    // Print an error message and exit
                    echo "CREATE_SAMPLE_EVENT: Could not create RSO event location with name " . $location_name . "\n\n";
                    exit(1);

                }

            }
            else
            {

                // Get the location ID
                $location_id = $location["id"];

            }

            // Create the event
            $event_id = create_event($event_name, $event_description, $event_category, $rso_admin_email, $event_phone_number, $event_date, $event_start_time, $event_end_time, $location_id);

            // Check if the event failed to be created
            if (!$event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not create RSO event with name " . $event_name . "\n\n";
                exit(1);

            }

            // Create the rso event
            $rso_event_id = create_rso_event_by_event_and_rso_id($event_id, $rso_id);

            // Check if the rso event failed to be created
            if (!$rso_event_id)
            {

                // Print an error message and exit
                echo "CREATE_SAMPLE_EVENT: Could not add event to RSO events with name " . $event_name . "\n\n";
                exit(1);

            }

            // Success message
            echo "CREATE_SAMPLE_EVENT: Created RSO event with name " . $event_name . "\n\n";

        }

    }

    // Create the sample public events
    create_sample_public_events();

    // Create the sample private events
    create_sample_private_events();

    // Create the sample RSO events
    create_sample_rso_events();

?>