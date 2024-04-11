<?php

    // Fetch the sample data from the users.json file
    $json_data = file_get_contents("./init/json/users.json");
    $sample_data = json_decode($json_data, true);

    // Include user.php
    include_once "/var/www/html/php/user.php";

    // Loop through the sample data and insert each user into the database
    foreach ($sample_data as $user_data) 
    {

        // Get the user data
        $user_first_name = $user_data["first_name"];
        $user_last_name = $user_data["last_name"];
        $user_email = $user_data["university_email"];
        $user_password = $user_data["password"];

        // Check if the user already exists
        if (get_user_by_university_email($user_email) === null)
        {

            // Create the user
            $success = create_user($user_first_name, $user_last_name, $user_email, $user_password);

            // Check if the user was created successfully
            if ($success)
                echo "CREATE_SAMPLE_USER: User created successfully: $user_email\n\n";
            else
                echo "CREATE_SAMPLE_USER: Failed to create user: $user_email\n\n";
            
        }

    }
    
?>