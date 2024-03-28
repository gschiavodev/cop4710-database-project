<?php

    // Include the database connection information
    include_once "database.php";

    function get_user_by_university_email($user_university_email)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get the user information
        $sql = "SELECT * FROM user WHERE university_email = ?";

        // Prepare the query
        $select_user = $conn->prepare($sql);
        $select_user->bind_param("s", $user_university_email);
        $select_user->execute();

        // Get the result of the query
        $user_result = $select_user->get_result();

        // Get the user information
        $user = $user_result->fetch_assoc();

        // Close the database connection
        close_connection_to_database($conn);

        // Check if the user exists
        return ($user) ? $user : false;

    }

    function insert_new_user($user_first_name, $user_last_name, $user_email, $user_password)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to insert a new user
        $sql = "INSERT INTO user (first_name, last_name, university_email, password) VALUES (?, ?, ?, ?)";

        // Hash the password
        $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Prepare the query
        $insert_user = $conn->prepare($sql);
        $insert_user->bind_param("ssss", $user_first_name, $user_last_name, $user_email, $user_hashed_password);

        // Execute the query to insert a new user
        $insert_user->execute();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the result of the query
        return $insert_user;

    }

?>