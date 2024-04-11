<?php


    // Include the database connection file
    include_once "database.php";

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

    function create_rso_event_by_event_id($event_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an INSERT statement to create the RSO event
        $create_rso_event = $conn->prepare("INSERT INTO rso_event (id, rso_id) VALUES (?, ?)");
        $create_rso_event->bind_param("ii", $event_id, $rso_id);
        $create_rso_event->execute();

        // Get the ID of the created RSO event
        $rso_event_id = $create_rso_event->insert_id;

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the ID of the created RSO event
        return $rso_event_id;
        
    }

?>