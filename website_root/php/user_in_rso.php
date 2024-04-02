<?php

    // Include the database connection information
    include_once "database.php";

    function add_user_to_rso_by_ids($user_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Query to add a user to an RSO
        $sql = "INSERT INTO user_in_rso (user_id, rso_id) VALUES (?, ?)";

        // Prepare an INSERT statement to add a user to an RSO
        $add_user_to_rso = $conn->prepare($sql);
        $add_user_to_rso->bind_param("ii", $user_id, $rso_id);
        $add_user_to_rso->execute();

        // Close connection to the database
        close_connection_to_database($conn);
        
        // Return the result of the INSERT query
        return $add_user_to_rso;

    }

    function get_users_in_rso_by_rso_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Query to get all the users in the RSO
        $sql = "SELECT * FROM user_in_rso WHERE rso_id = ?";

        // Prepare a SELECT statement to get all the users in the RSO
        $select_users_in_rso = $conn->prepare($sql);
        $select_users_in_rso->bind_param("i", $rso_id);
        $select_users_in_rso->execute();

        // Get the result of the SELECT query
        $select_users_in_rso_result = $select_users_in_rso->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $select_users_in_rso_result;
        
    }

?>