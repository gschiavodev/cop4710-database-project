<?php

    // Include the database connection file
    include_once "database.php";  

    function is_admin($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get admins with the provided user id
        $statement = $conn->prepare("SELECT * FROM admin WHERE id = ?");
        $statement->bind_param("i", $user_id);
        $statement->execute();

        // Get the result of the SELECT query
        $result = $statement->get_result();

        // Check if the user exists and set as admin
        $is_admin = $result->num_rows != 0 ? true : false;

        // Close database connection
        close_connection_to_database($conn);

        // Return if the user is an admin
        return $is_admin;

    }

    function is_super_admin($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get super admins with the provided user id
        $sql = $conn->prepare("SELECT * FROM super_admin WHERE id = ?");
        $sql->bind_param("i", $user_id);
        $sql->execute();

        // Get the result of the SELECT query
        $result = $sql->get_result();

        // Check if the user exists and set as super admin
        $is_super_admin = $result->num_rows != 0 ? true : false;

        // Close database connection
        close_connection_to_database($conn);

        // Return if the user is a super admin
        return $is_super_admin;

    }

?>