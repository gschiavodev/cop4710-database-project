<?php

    session_start();

    // Make sure the user is a super admin
    if (!$_SESSION['user_is_super_admin']) 
    {

        $_SESSION['message_manage_admin'] = "You do not have permission to perform this action";
        header('Location: ../../admin.html#section_manage_admins');
        exit();

    }

    // Sanitize email
    $university_email = filter_input(INPUT_POST, 'university_email', FILTER_SANITIZE_EMAIL);

    // Validate email
    if (!filter_var($university_email, FILTER_VALIDATE_EMAIL)) 
    {

        $_SESSION['message_manage_admin'] = "Invalid email format";
        header('Location: ../../admin.html#section_manage_admins');
        exit();

    }

    // Sanitize action
    $action = strtolower(filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING));

    // Validate action
    switch ($action) 
    {

        // Promote user to admin
        case 'promote':

            // Include user.php to get the user information
            include_once "../user.php";

            // Get the user information
            $user = get_user_by_university_email($university_email);

            // Check if the user exists
            if (!$user) 
            {

                $_SESSION['message_manage_admin'] = "User does not exist";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }

            // Include the admin.php file to check if the user is an admin
            include_once "../admin.php";

            // Check if the user is an admin
            if (is_admin($user['id'])) 
            {

                $_SESSION['message_manage_admin'] = "User is already an admin";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }
            
            // Create an admin with the user information
            if (!create_admin_by_user_id($user['id'])) 
            {

                $_SESSION['message_manage_admin'] = "Failed to promote user to admin";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }

            // Set the message
            $_SESSION['message_manage_admin'] = "User promoted to admin";
            header('Location: ../../admin.html#section_manage_admins');
            exit();
        
        // Demote user from admin
        case 'demote':

            // Include user.php to get the user information
            include_once "../user.php";

            // Get the user information
            $user = get_user_by_university_email($university_email);

            // Check if the user exists
            if (!$user) 
            {

                $_SESSION['message_manage_admin'] = "User does not exist";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }

            // Include the admin.php file to check if the user is an admin
            include_once "../admin.php";

            // Check if the user is an admin
            if (!is_admin($user['id'])) 
            {

                $_SESSION['message_manage_admin'] = "User is not an admin";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }

            // Delete the admin with the user information
            if (!delete_admin_by_user_id($user['id'])) 
            {

                $_SESSION['message_manage_admin'] = "Failed to demote user from admin";
                header('Location: ../../admin.html#section_manage_admins');
                exit();

            }

            // Set the message
            $_SESSION['message_manage_admin'] = "User demoted from admin";
            header('Location: ../../admin.html#section_manage_admins');
            exit();

        default:
            
            $_SESSION['message_manage_admin'] = "Invalid action";
            header('Location: ../../admin.html#section_manage_admins');
            exit();

    }

?>