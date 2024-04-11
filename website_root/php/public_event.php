<?php

    // Include the database connection information
    include_once "database.php";

    function get_public_events()
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get all public events
        $sql = "SELECT E.* FROM event AS E INNER JOIN public_event AS PE ON E.id = PE.id ORDER BY E.date ASC, E.time ASC";

        // Execute the query to get all public events
        $select_public_events = mysqli_query($conn, $sql);

        // Close the database connection
        close_connection_to_database($conn);

        // Check if the query was successful
        return ($select_public_events) ? $select_public_events : false;
        
    }

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

    function create_public_event_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an INSERT statement to create the public event
        $create_public_event = $conn->prepare("INSERT INTO public_event (id, is_approved) VALUES (?, 0)");
        $create_public_event->bind_param("i", $event_id);
        $create_public_event->execute();

        // Get the ID of the created public event
        $public_event_id = $create_public_event->insert_id;

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the ID of the created public event
        return $public_event_id;
        
    }

    function approve_public_event_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an UPDATE statement to approve the public event
        $approve_public_event = $conn->prepare("UPDATE public_event SET is_approved = 1 WHERE id = ?");
        $approve_public_event->bind_param("i", $event_id);
        $approve_public_event->execute();

        // Close connection to the database
        close_connection_to_database($conn);
        
    }

    function deny_public_event_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Include the event.php file
        include_once "event.php";

        // Delete the event from the events table
        delete_event_by_event_id($event_id);

        // Close connection to the database
        close_connection_to_database($conn);
        
    }

?>
