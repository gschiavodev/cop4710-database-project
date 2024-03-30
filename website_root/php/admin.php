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

    function create_admin_by_user_id($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a INSERT statement to create an admin with the user information
        $statement = $conn->prepare("INSERT INTO admin (id) VALUES (?)");
        $statement->bind_param("i", $user_id);
        $statement->execute();

        // Close database connection
        close_connection_to_database($conn);

        // Return if the admin was created
        return $statement->affected_rows > 0;

    }

    function delete_admin_by_user_id($user_id)
    {

        // Connect to database
        $conn = connect_to_database();

        // Prepare a DELETE statement to delete an admin with the user information
        $statement = $conn->prepare("DELETE FROM admin WHERE id = ?");
        $statement->bind_param("i", $user_id);
        $statement->execute();

        // Close database connection
        close_connection_to_database($conn);

        // Return if the admin was deleted
        return $statement->affected_rows != 0 ? true : false;

    }

?>