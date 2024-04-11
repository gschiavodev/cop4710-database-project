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
        return $location;

    }

    function get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zip_code, $latitude = null, $longitude = null)
    {

        // Check if only one of latitude or longitude is set
        if (($latitude === null && $longitude !== null) || ($latitude !== null && $longitude === null)) 
        {

            // Return an error or a specific value
            return "Error: Both latitude and longitude must be provided if any.";

        }
        
        // Open a connection to the database
        $conn = connect_to_database();

        // Base query
        $sql = "SELECT * FROM location WHERE LOWER(TRIM(address_line_01)) = LOWER(?) AND LOWER(TRIM(address_line_02)) = LOWER(?) AND LOWER(TRIM(city)) = LOWER(?) AND LOWER(TRIM(state)) = LOWER(?) AND LOWER(TRIM(zip_code)) = LOWER(?)";

        // Check if address line 02 is null
        $address_line_02 = ($address_line_02 === null) ? "" : $address_line_02;

        // Trim the input values
        $address_line_01 = trim($address_line_01);
        $address_line_02 = trim($address_line_02);
        $city = trim($city);
        $state = trim($state);
        $zip_code = trim($zip_code);

        // Array to hold parameters and their types
        $params = array($address_line_01, $address_line_02, $city, $state, $zip_code);
        $types = array_fill(0, 5, 's'); // All these parameters are strings

        // If latitude and longitude are provided, add them to the query
        if ($latitude !== null && $longitude !== null) 
        {
            $sql .= " AND latitude = ? AND longitude = ?";
            array_push($params, $latitude, $longitude);
            array_push($types, 'i', 'i'); // Latitude and longitude are integers
        } 
        else 
        {
            $sql .= " AND latitude IS NULL AND longitude IS NULL";
        }

        // Prepare the query
        $select_location = $conn->prepare($sql);

        // Bind the parameters
        $select_location->bind_param(implode('', $types), ...$params);

        // Execute the query
        $select_location->execute();

        // Get the result of the query
        $location_result = $select_location->get_result();

        // Get the location information
        $location = $location_result->fetch_assoc();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the location
        return $location;

    }

    function create_location($name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude = null, $longitude = null)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Check if address line 02 is null
        $address_line_02 = ($address_line_02 === null) ? "" : $address_line_02;

        // Query to create a new location
        $insert_location = $conn->prepare("INSERT INTO location (name, address_line_01, address_line_02, city, state, zip_code, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_location->bind_param("ssssssii", $name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);
        $insert_location->execute();

        // Get the ID of the created location
        $location_id = $insert_location->insert_id;

        // Close the database connection
        close_connection_to_database($conn);

        // Return if the location was created
        return $location_id;

    }

?>