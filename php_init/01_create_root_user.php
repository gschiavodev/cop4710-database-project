<?php

    // Root user information
    $root_first_name = "root";
    $root_last_name = "";
    $root_email = "root@localhost.com";
    $root_password = "password";

    // Include the database configuration file
    include "/var/www/html/php/database.php";

    // Connect to the database
    $conn = connect_to_database();

    // Include the user functions
    include "/var/www/html/php/user.php";

    // Check if the root user exists
    $result = get_user_by_university_email($root_email);

    // If the root user does not exist, create it
    if ($result->num_rows == 0)
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

        }
        else 
        {

            // Failed to insert the root user
            echo "Failed to insert the root user!";

        }

    }

    // Close the connection
    close_connection_to_database($conn);

?>