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

?>
