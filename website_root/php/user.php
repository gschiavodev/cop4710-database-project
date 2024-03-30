<?php

    // Include the database connection information
    include_once "database.php";

    function get_user_by_university_email($user_university_email)
    {

        // Filter and validate the university email
        $user_university_email = filter_var($user_university_email, FILTER_SANITIZE_EMAIL);

        // Validate the university email
        if (!filter_var($user_university_email, FILTER_VALIDATE_EMAIL))
            return null;

        // Make sure the university email is lowercase
        $user_university_email = strtolower($user_university_email);

        // Open a connection to the database
        $conn = connect_to_database();

        // Query to get the user by university email
        $sql = "SELECT * FROM user WHERE university_email = ?";

        // Prepare the query
        $get_user = $conn->prepare($sql);
        $get_user->bind_param("s", $user_university_email);

        // Execute the query to get the user by university email
        $get_user->execute();

        // Get the result of the query
        $result = $get_user->get_result();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the user
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;

    }

    function create_user($user_first_name, $user_last_name, $user_email, $user_password)
    {

        // Open a connection to the database
        $conn = connect_to_database();

        // Hash the password
        $user_hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Prepare the query to insert the new user
        $insert_user = $conn->prepare("INSERT INTO user (first_name, last_name, university_email, password) VALUES (?, ?, ?, ?)");
        $insert_user->bind_param("ssss", $user_first_name, $user_last_name, $user_email, $user_hashed_password);
        $insert_user->execute();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the result of the query
        return $insert_user->affected_rows > 0;

    }

?>