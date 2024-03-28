<?php

    // Include the database connection information
    include_once "database.php";

    function get_location($location_id)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get the location information
        $sql = "SELECT * FROM location WHERE id = ?";

        // Prepare the query
        $select_location = $conn->prepare($sql);
        $select_location->bind_param("i", $location_id);
        $select_location->execute();

        // Get the result of the query
        $location_result = $select_location->get_result();

        // Get the location information
        $location = $location_result->fetch_assoc();

        // Close the database connection
        close_connection_to_database($conn);

        // Check if the location exists
        return ($location) ? $location : false;

    }


?>