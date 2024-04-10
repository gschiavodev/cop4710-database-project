<?php

    // Include the database connection information
    include_once "database.php";

    function add_user_by_id_to_rso_by_id($user_id, $rso_id)
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

        // Prepare and execute the SELECT statement
        $select_rso_members = $conn->prepare("SELECT user.* FROM user JOIN user_in_rso ON user.id = user_in_rso.user_id WHERE user_in_rso.rso_id = ?");
        $select_rso_members->bind_param("i", $rso_id);
        $select_rso_members->execute();

        // Get the result of the SELECT query
        $rso_members = $select_rso_members->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso_members;
        
    }

    function check_if_user_in_rso_by_rso_id_and_user_id($rso_id, $user_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to check if the user is in the RSO
        $select_user_in_rso = $conn->prepare("SELECT * FROM user_in_rso WHERE user_id = ? AND rso_id = ?");
        $select_user_in_rso->bind_param("ii", $user_id, $rso_id);
        $select_user_in_rso->execute();

        // Get the result of the SELECT query
        $user_in_rso = $select_user_in_rso->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return if the user is in the RSO
        return $user_in_rso->num_rows > 0;
        
    }

?>