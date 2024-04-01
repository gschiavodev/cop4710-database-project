<?php

    // Read and parse the JSON file
    $json_data = file_get_contents("./init/json/root_user.json");
    $root_user = json_decode($json_data, true);

    // Access the data
    $root_first_name = $root_user['first_name'];
    $root_last_name = $root_user['last_name'];
    $root_email = $root_user['email'];
    $root_password = $root_user['password'];

    // Include the user functions
    include "/var/www/html/php/user.php";

    // Check if the root user exists
    $result = get_user_by_university_email($root_email);

    // If the root user does not exist, create it
    if (!$result)
    {

        // Include the admin and super admin functions
        include "/var/www/html/php/admin.php";
        include "/var/www/html/php/super_admin.php";

        // Insert the root user
        $success = create_user($root_first_name, $root_last_name, $root_email, $root_password);

        // If user was inserted successfully
        if ($success)
        {

            // Get the user id
            $user = get_user_by_university_email($root_email);

            // Add the root user as an admin
            create_admin_by_user_id($user['id']);

            // Add the root user as a super admin
            create_super_admin_by_user_id($user['id']);

            // Success
            echo "CREATE_ROOT_USER: Root user created successfully!\n\n";

        }
        else 
        {

            // Failed to insert the root user
            echo "CREATE_ROOT_USER: Failed to insert the root user!\n\n";

        }

    }
    else 
    {

        // Root user already exists
        echo "CREATE_ROOT_USER: Root user already exists!\n\n";

    }
    
?>