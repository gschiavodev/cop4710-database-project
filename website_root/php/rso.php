<?php

    // Include the database connection information
    include_once "database.php";

    function get_rsos()
    {

        // Connect to the database
        $conn = connect_to_database();

        // Query to get all the RSOs
        $sql = "SELECT * FROM rso";

        // Prepare a SELECT statement to get all the RSOs
        $select_rsos = $conn->prepare($sql);
        $select_rsos->execute();

        // Get the result of the SELECT query
        $select_rsos_result = $select_rsos->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $select_rsos_result;
        
    }

    function get_owned_rsos($admin_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Query to get the RSOs that the admin owns
        $sql = "SELECT * FROM rso WHERE admin_id = ?";

        // Prepare a SELECT statement to get the RSOs that the admin owns
        $select_owned_rsos = $conn->prepare($sql);
        $select_owned_rsos->bind_param("i", $admin_id);
        $select_owned_rsos->execute();

        // Get the result of the SELECT query
        $select_owned_rsos_result = $select_owned_rsos->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $select_owned_rsos_result;
        
    }

    function get_rso_members($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the members of the RSO
        $sql = "SELECT user.* FROM user JOIN user_in_rso ON user.id = user_in_rso.user_id WHERE user_in_rso.rso_id = ?";

        // Prepare and execute the SELECT statement
        $select_rso_members = $conn->prepare($sql);
        $select_rso_members->bind_param("i", $rso_id);
        $select_rso_members->execute();

        // Get the result of the SELECT query
        $rso_members = $select_rso_members->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso_members;
        
    }

?>