<?php

    // Include the database connection information
    include_once "database.php";

    function get_locations()
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get the location information
        $sql = "SELECT * FROM location";

        // Prepare the query
        $select_locations = $conn->prepare($sql);
        $select_locations->execute();

        // Get the result of the query
        $select_locations_result = $select_locations->get_result();

        // Close the database connection
        close_connection_to_database($conn);

        // Check if the locations exist
        return $select_locations_result;

    }

    function get_location_by_id($location_id)
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

    function get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zipcode, $latitude = null, $longitude = null)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get the location information
        $sql = "SELECT * FROM location WHERE address_line_01 = ? AND address_line_02 = ? AND city = ? AND state = ? AND zip_code = ? AND latitude = ? AND longitude = ?";

        // Prepare the query
        $select_location = $conn->prepare($sql);
        $select_location->bind_param("sssssii", $address_line_01, $address_line_02, $city, $state, $zipcode, $latitude, $longitude);
        $select_location->execute();

        // Get the result of the query
        $location_result = $select_location->get_result();

        // Get the location information
        $location = $location_result->fetch_assoc();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the location information
        return $location;

    }


    function create_location($name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude = null, $longitude = null)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to create a new location
        $insert_location = $conn->prepare("INSERT INTO location (name, address_line_01, address_line_02, city, state, zip_code, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_location->bind_param("ssssssii", $name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);
        $insert_location->execute();

        // Close the database connection
        close_connection_to_database($conn);

        // Return if the location was created
        return $insert_location;

    }

?>