<?php

    // Include the database connection file
    include_once "database.php";

    function get_private_events_by_rso_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the private events by RSO ID
        $select_private_events_by_rso_id = $conn->prepare("SELECT * FROM event WHERE event.id IN (SELECT private_event.id FROM private_event WHERE private_event.rso_id = ?)");
        $select_private_events_by_rso_id->bind_param("i", $rso_id);
        $select_private_events_by_rso_id->execute();

        // Get the result of the SELECT query
        $private_events_by_rso_id = $select_private_events_by_rso_id->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $private_events_by_rso_id;
        
    }

    function get_private_events_by_university_id($university_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Include the RSO.php file to get the RSOs
        include_once "rso.php";

        // Get the approved RSOs
        $rsos_by_university_id = get_approved_rsos_by_university_id($university_id);
        
        // Fetch the RSO IDs from the result object and ensure they are integers
        $approved_rsos_ids = array();
        while ($row = $rsos_by_university_id->fetch_assoc()) {
            $approved_rsos_ids[] = intval($row['id']);
        }
        
        // Check if there are any approved RSOs
        if (empty($approved_rsos_ids)) 
        {

            // Close connection to the database
            close_connection_to_database($conn);
        
            // Return an empty result
            return null;

        }
        
        // Convert the array of approved RSO IDs to a comma-separated string
        $approved_rsos_string = implode(',', $approved_rsos_ids);
        
        // Prepare the SQL statement
        $select_private_events_by_university_id = $conn->prepare("SELECT * FROM event WHERE event.id IN (SELECT private_event.id FROM private_event WHERE private_event.rso_id IN ($approved_rsos_string))");
        
        // Execute the SQL statement
        $select_private_events_by_university_id->execute();
        
        // Get the result of the SELECT query
        $private_events_by_university_id = $select_private_events_by_university_id->get_result();
        
        // Close connection to the database
        close_connection_to_database($conn);
        
        // Return the result of the SELECT query
        return $private_events_by_university_id;

    }

    function get_private_event_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the private event by event ID
        $select_private_event_by_event_id = $conn->prepare("SELECT * FROM private_event WHERE id = ?");
        $select_private_event_by_event_id->bind_param("i", $event_id);
        $select_private_event_by_event_id->execute();

        // Get the result of the SELECT query
        $private_event_by_event_id = $select_private_event_by_event_id->get_result();

        // Get the private event data
        $private_event_by_event_id = $private_event_by_event_id->fetch_assoc();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $private_event_by_event_id;
        
    }

    function create_private_event_by_event_and_rso_id($event_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an INSERT statement to create the private event
        $create_private_event = $conn->prepare("INSERT INTO private_event (id, rso_id) VALUES (?, ?)");
        $create_private_event->bind_param("ii", $event_id, $rso_id);
        $create_private_event->execute();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the ID of the created private event
        return $event_id;

    }

?>