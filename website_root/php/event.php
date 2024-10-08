<?php

    // Include the database connection file
    include_once "database.php";

    function get_event_by_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the event by ID
        $get_event = $conn->prepare("SELECT * FROM event WHERE id = ?");
        $get_event->bind_param("i", $event_id);
        $get_event->execute();

        // Get the event
        $event = $get_event->get_result()->fetch_assoc();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the event
        return $event;

    }

    function create_event($event_name, $event_description, $event_category, $event_email, $event_phone_number, $event_date, $event_start_time, $event_end_time, $location_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an INSERT statement to create the event (location_id, name, description, category, date, time, phone_number, email)
        $create_event = $conn->prepare("INSERT INTO event (location_id, name, description, category, email, phone_number, date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $create_event->bind_param("issssssss", $location_id, $event_name, $event_description, $event_category, $event_email, $event_phone_number, $event_date, $event_start_time, $event_end_time);
        
        // Check if execution of the statement was successful
        if (!$create_event->execute())
        {

            // Close connection to the database
            close_connection_to_database($conn);

            // Return null
            return null;

        }

        // Get the ID of the created event
        $event_id = $create_event->insert_id;

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the ID of the created event
        return $event_id;

    }

    function delete_event_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a DELETE statement to delete the event
        $delete_event = $conn->prepare("DELETE FROM event WHERE id = ?");
        $delete_event->bind_param("i", $event_id);
        $delete_event->execute();

        // Close connection to the database
        close_connection_to_database($conn);

    }

?>