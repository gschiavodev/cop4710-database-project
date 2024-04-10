<?php

    // Include the database connection file
    include_once "database.php";

    function get_approved_public_events()
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the approved public events
        $select_approved_public_events = $conn->prepare("SELECT * FROM event WHERE event.id IN (SELECT public_event.id FROM public_event WHERE public_event.is_approved = 1)");
        $select_approved_public_events->execute();

        // Get the result of the SELECT query
        $approved_public_events = $select_approved_public_events->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $approved_public_events;
        
    }

    function get_unapproved_public_events()
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the unapproved public events
        $select_unapproved_public_events = $conn->prepare("SELECT * FROM event WHERE event.id IN (SELECT public_event.id FROM public_event WHERE public_event.is_approved = 0)");
        $select_unapproved_public_events->execute();

        // Get the result of the SELECT query
        $unapproved_public_events = $select_unapproved_public_events->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $unapproved_public_events;
        
    }

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

        /*

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
            return new mysqli_result();

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
        */


    }

    function get_rso_events_by_rso_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the RSO events by RSO ID
        $select_rso_events_by_rso_id = $conn->prepare("SELECT * FROM event WHERE event.id IN (SELECT rso_event.id FROM rso_event WHERE rso_event.rso_id = ?)");
        $select_rso_events_by_rso_id->bind_param("i", $rso_id);
        $select_rso_events_by_rso_id->execute();

        // Get the result of the SELECT query
        $rso_events_by_rso_id = $select_rso_events_by_rso_id->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso_events_by_rso_id;
        
    }

?>