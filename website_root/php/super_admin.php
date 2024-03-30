<?php

    // Include the database connection file
    include_once "database.php";  

    function is_super_admin($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a SELECT statement to get super admins with the provided user id
        $statement = $conn->prepare("SELECT * FROM super_admin WHERE id = ?");
        $statement->bind_param("i", $user_id);
        $statement->execute();

        // Get the result of the SELECT query
        $result = $statement->get_result();

        // Check if the user exists and set as super admin
        $is_super_admin = $result->num_rows != 0 ? true : false;

        // Close database connection
        close_connection_to_database($conn);

        // Return if the user is a super admin
        return $is_super_admin;

    }

    function create_super_admin_by_user_id($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a INSERT statement to create a super admin with the user information
        $statement = $conn->prepare("INSERT INTO super_admin (id) VALUES (?)");
        $statement->bind_param("i", $user_id);
        $statement->execute();

        // Close database connection
        close_connection_to_database($conn);

        // Return if the super admin was created
        return $statement->affected_rows != 0 ? true : false;

    }

?>