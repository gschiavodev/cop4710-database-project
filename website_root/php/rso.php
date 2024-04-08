<?php

    // Include the database connection information
    include_once "database.php";

    function get_rso_by_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the RSO with the provided ID
        $select_rso = $conn->prepare("SELECT * FROM rso WHERE id = ?");
        $select_rso->bind_param("i", $rso_id);
        $select_rso->execute();

        // Get the result of the SELECT query
        $rso = $select_rso->get_result()->fetch_assoc();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rso;
        
    }

    function get_approved_rsos()
    {

        // Only get the approved RSOs
        $is_approved = true;

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get all the RSOs
        $select_rsos = $conn->prepare("SELECT * FROM rso WHERE is_approved = ?");
        $select_rsos->bind_param("i", $is_approved);
        $select_rsos->execute();

        // Get the result of the SELECT query
        $select_rsos_result = $select_rsos->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $select_rsos_result;
        
    }

    function get_unapproved_rsos()
    {

        // Only get the unapproved RSOs
        $is_approved = false;

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get all the unapproved RSOs
        $select_rsos = $conn->prepare("SELECT * FROM rso WHERE is_approved = ?");
        $select_rsos->bind_param("i", $is_approved);
        $select_rsos->execute();

        // Get the result of the SELECT query
        $select_rsos_result = $select_rsos->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $select_rsos_result;
        
    }

    function get_approved_rsos_by_university_id($university_id)
    {

        // Only get the approved RSOs
        $is_approved = true;

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the RSOs by university ID
        $select_rsos_by_university_id = $conn->prepare("SELECT * FROM rso WHERE university_id = ? AND is_approved = ?");
        $select_rsos_by_university_id->bind_param("ii", $university_id, $is_approved);
        $select_rsos_by_university_id->execute();

        // Get the result of the SELECT query
        $rsos_by_university_id = $select_rsos_by_university_id->get_result();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the result of the SELECT query
        return $rsos_by_university_id;
        
    }

    function get_owned_rsos($admin_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get the RSOs that the admin owns
        $select_owned_rsos = $conn->prepare("SELECT * FROM rso WHERE admin_id = ?");
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

    function create_rso($rso_name, $rso_description, $rso_university_id, $user_ids)
    {

        // Check that there are at least 4 users in the RSO
        if (count($user_ids) < 4)
            return null;

        // Admin ID is the first user ID in the list
        $admin_id = $user_ids[0];

        // RSO is not approved by default
        $is_approved = false;

        // Include the admin.php
        include_once "admin.php";

        // Check if the user who created the RSO is an admin
        if (!is_admin($admin_id))
        {

            // Make the user who created the RSO an admin
            create_admin_by_user_id($admin_id);

        }

        // Connect to the database
        $conn = connect_to_database();

        // Query to insert the RSO into the database
        $sql = "INSERT INTO rso (name, description, university_id, admin_id, is_approved) VALUES (?, ?, ?, ?, ?)";
        
        // Prepare and execute the INSERT statement
        $insert_rso = $conn->prepare($sql);
        $insert_rso->bind_param("ssiii", $rso_name, $rso_description, $rso_university_id, $admin_id, $is_approved);
        $insert_rso->execute();

        // Check if the RSO was created successfully
        if ($insert_rso->affected_rows <= 0)
        {

            // Close connection to the database
            close_connection_to_database($conn);

            // Return null if the RSO was not created successfully
            return null;

        }

        // Get the ID of the RSO that was just created
        $rso_id = $conn->insert_id;

        // Include the user_in_rso.php
        include_once "user_in_rso.php";

        // Add the users to the RSO
        foreach ($user_ids as $user_id)
        {

            // Add the user to the RSO
            add_user_to_rso_by_ids($user_id, $rso_id);
        
        }

        // Close connection to the database
        close_connection_to_database($conn);

        // Return the ID of the RSO that was just created
        return $rso_id;
        
    }

    function delete_rso_by_id($rso_id)
    {

        // Check if the admin who owns the RSO is an admin of the system
        $rso = get_rso_by_id($rso_id);
        $admin_id = $rso['admin_id'];

        // Include the admin.php
        include_once "admin.php";

        // Check if the user who created the RSO is an admin
        if (is_admin($admin_id))
        {

            // Check if the user is an admin of any other RSOs
            $owned_rsos = get_owned_rsos($admin_id);

            // If the user is not an admin of any other RSOs, remove the admin status
            if ($owned_rsos->num_rows == 1)
            {

                // Include the super_admin.php
                include_once "../super_admin.php";

                // If the user is a super admin, do not remove the admin status
                if (!is_super_admin($admin_id))
                {

                    // Remove the admin status from the user
                    delete_admin_by_user_id($admin_id);

                }

            }

        }

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a DELETE statement to delete the RSO
        $delete_rso = $conn->prepare("DELETE FROM rso WHERE id = ?");
        $delete_rso->bind_param("i", $rso_id);
        $delete_rso->execute();

        // Close connection to the database
        close_connection_to_database($conn);

        // Return if the RSO was deleted
        return $delete_rso->affected_rows > 0;
        
    }

    // Function to approve an RSO
    function approve_rso_by_id($rso_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare a UPDATE statement to approve the RSO
        $approve_rso = $conn->prepare("UPDATE rso SET is_approved = 1 WHERE id = ?");
        $approve_rso->bind_param("i", $rso_id);
        $approve_rso->execute();

        // Close connection to the database
        close_connection_to_database($conn);

    }

    // Function to deny an RSO
    function deny_rso_by_id($rso_id)
    {

        // Delete the RSO
        delete_rso_by_id($rso_id);

    }

?>