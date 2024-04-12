<?php

    // Include the database connection information
    include_once "database.php";

    function add_user_by_id_to_rso_by_id($user_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Default the user to not approved
        $is_approved = false;

        // Include necessary files
        include_once "rso.php";
        include_once "university.php";

        // Get the university id of the RSO
        $rso_university = get_university_by_rso_id($rso_id);

        // Check if the university was found
        if (!$rso_university)
        {

            // Close connection to the database
            close_connection_to_database($conn);

            // Return false
            return false;

        }

        // Get the university id of the user
        $user_university = get_university_by_user_id($user_id);

        // Check if the university was found
        if (!$user_university)
        {

            // Close connection to the database
            close_connection_to_database($conn);

            // Return false
            return false;

        }

        // Get the rso university id and the user university id
        $rso_university_id = $rso_university["id"];
        $user_university_id = $user_university["id"];

        // Check if the user is in the same university as the RSO
        if ($rso_university_id != $user_university_id)
        {

            // Close connection to the database
            close_connection_to_database($conn);

            // Return false
            return false;

        }

        // Prepare an INSERT statement to add a user to an RSO
        $add_user_to_rso = $conn->prepare("INSERT INTO user_in_rso (user_id, rso_id, is_approved) VALUES (?, ?, ?)");
        $add_user_to_rso->bind_param("iii", $user_id, $rso_id, $is_approved);
        $add_user_to_rso->execute();

        // Get the insert id of the user in RSO
        $user_in_rso_id = $add_user_to_rso->insert_id;

        // Close connection to the database
        close_connection_to_database($conn);
        
        // Return the result of the INSERT query
        return $user_in_rso_id;

    }

    function remove_user_by_id_from_rso_by_id($user_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a DELETE statement to remove a user from an RSO
        $remove_user_from_rso = $conn->prepare("DELETE FROM user_in_rso WHERE user_id = ? AND rso_id = ?");
        $remove_user_from_rso->bind_param("ii", $user_id, $rso_id);
        $remove_user_from_rso->execute();

        // Close connection to the database
        close_connection_to_database($conn);
        
        // Return the result of the DELETE query
        return $remove_user_from_rso->affected_rows > 0;

    }

    function get_approved_users_in_rso_by_rso_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare and execute the SELECT statement
        $select_rso_members = $conn->prepare("SELECT user.* FROM user JOIN user_in_rso ON user.id = user_in_rso.user_id WHERE user_in_rso.rso_id = ? AND user_in_rso.is_approved = 1");
        $select_rso_members->bind_param("i", $rso_id);
        $select_rso_members->execute();

        // Get the result of the SELECT query
        $rso_members = $select_rso_members->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso_members;
        
    }

    function get_unapproved_users_in_rso_by_rso_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare and execute the SELECT statement
        $select_rso_members = $conn->prepare("SELECT user.* FROM user JOIN user_in_rso ON user.id = user_in_rso.user_id WHERE user_in_rso.rso_id = ? AND user_in_rso.is_approved = 0");
        $select_rso_members->bind_param("i", $rso_id);
        $select_rso_members->execute();

        // Get the result of the SELECT query
        $rso_members = $select_rso_members->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso_members;
        
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

    function check_if_user_in_rso_and_approved_by_rso_id_and_user_id($rso_id, $user_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare and execute the SELECT statement
        $select_user_in_rso = $conn->prepare("SELECT * FROM user_in_rso WHERE rso_id = ? AND user_id = ? AND is_approved = 1");
        $select_user_in_rso->bind_param("ii", $rso_id, $user_id);
        $select_user_in_rso->execute();

        // Get the result of the SELECT query
        $user_in_rso = $select_user_in_rso->get_result()->fetch_assoc();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $user_in_rso != null;
        
    }

    function check_if_user_in_rso_and_unapproved_by_rso_id_and_user_id($rso_id, $user_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare and execute the SELECT statement
        $select_user_in_rso = $conn->prepare("SELECT * FROM user_in_rso WHERE rso_id = ? AND user_id = ? AND is_approved = 0");
        $select_user_in_rso->bind_param("ii", $rso_id, $user_id);
        $select_user_in_rso->execute();

        // Get the result of the SELECT query
        $user_in_rso = $select_user_in_rso->get_result()->fetch_assoc();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $user_in_rso != null;
        
    }

    function approve_user_in_rso_by_user_and_rso_id($user_id, $rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare an UPDATE statement to approve a user in an RSO
        $approve_user_in_rso = $conn->prepare("UPDATE user_in_rso SET is_approved = 1 WHERE user_id = ? AND rso_id = ?");
        $approve_user_in_rso->bind_param("ii", $user_id, $rso_id);
        $approve_user_in_rso->execute();

        // Close connection to the database
        close_connection_to_database($conn);
        
        // Return the result of the UPDATE query
        return $approve_user_in_rso->affected_rows > 0;

    }

    function deny_user_in_rso_by_user_and_rso_id($user_id, $rso_id)
    {

        // Remove the user from the RSO
        $success = remove_user_by_id_from_rso_by_id($user_id, $rso_id);

        // Return the result of the DELETE query
        return $success;

    }


?>