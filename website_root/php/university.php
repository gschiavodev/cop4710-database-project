<?php

    // Include the database connection file
    include_once "database.php";

    function get_university_by_email($university_email)
    {

        // Check if the university email is set
        if ($university_email)
        {

            // Validate the email first
            if (!filter_var($university_email, FILTER_VALIDATE_EMAIL))
                return null;
    
            // Get the domain part of the email
            $email_parts = explode('@', $university_email);
            $domain = array_pop($email_parts);

            // Create a new database connection
            $conn = connect_to_database();

            // Query the database for the university with the given domain
            $select_college_domain = $conn->prepare("SELECT id FROM college_events.university WHERE email_domain = ?");
            $select_college_domain->bind_param("s", $domain);
            $select_college_domain->execute();

            // Get the result
            $select_college_domain_result = $select_college_domain->get_result();

            // Close the connection
            close_connection_to_database($conn);

            // If university was found, return them
            if ($select_college_domain_result->num_rows > 0) 
                return $select_college_domain_result;

            // No university found
            return null;

        }
        else
        {

            // University email is not set
            return null;

        }

    }

    function get_university_by_email_domain($email_domain)
    {

        // Create a new database connection
        $conn = connect_to_database();

        // Query the database for the university with the given domain
        $select_university = $conn->prepare("SELECT * FROM college_events.university WHERE email_domain = ?");
        $select_university->bind_param("s", $email_domain);
        $select_university->execute();

        // Get the result
        $select_university_result = $select_university->get_result();

        // Close the connection
        close_connection_to_database($conn);

        // If a university was found, return it
        if ($select_university_result->num_rows > 0) 
            return $select_university_result->fetch_assoc();

        // No university found
        return null;

    }

    function get_universities()
    {

        // Create a new database connection
        $conn = connect_to_database();

        // Query the database for all universities
        $select_universities = $conn->prepare("SELECT * FROM college_events.university");
        $select_universities->execute();

        // Get the result
        $select_universities_result = $select_universities->get_result();

        // Close the connection
        close_connection_to_database($conn);

        // If universities were found, return them
        if ($select_universities_result->num_rows > 0) 
            return $select_universities_result;

        // No universities found
        return null;
        
    }

    function create_university($name, $description, $email_domain, $location_id)
    {

        // Create a new database connection
        $conn = connect_to_database();

        // Query the database to create a new university
        $insert_university = $conn->prepare("INSERT INTO college_events.university (name, description, email_domain, location_id) VALUES (?, ?, ?, ?)");
        $insert_university->bind_param("sssi", $name, $description, $email_domain, $location_id);
        $insert_university->execute();

        // Close the connection
        close_connection_to_database($conn);

        // Return the result
        return $insert_university;

    }

?>