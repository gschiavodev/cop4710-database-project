<?php

    session_start();

    // Check if the university_email and password fields are set
    if (isset($_POST['university_email']) && isset($_POST['password']))
    {

        // Include the user.php file to get the user information
        include_once "../user.php";

        // Get the user information
        $user = get_user_by_university_email($_POST['university_email']);

        // Make sure the email is lowercase
        $_POST['university_email'] = strtolower($_POST['university_email']);

        // Check if the user exists
        if ($user)
        {

            // User exists, now we check the password
            if (password_verify($_POST['password'], $user['password'])) 
            {

                // Password is correct, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_university_email'] = $user['university_email'];
                
                // Include the admin.php and super_admin.php files to check if the user is an admin or super admin
                include_once "../super_admin.php";
                include_once "../admin.php";

                // Set sesstion variables for admin and super admin
                $_SESSION['user_is_super_admin'] = is_super_admin($user['id']);
                $_SESSION['user_is_admin'] = is_admin($user['id']);

                // Redirect to index.html
                header('Location: ../../index.html');
                exit();

            } 
            else 
            {

                // Password is incorrect
                $_SESSION['message_login'] = "Password is incorrect! Please enter the correct password.";
                header('Location: ../form/login.html');
                exit();

            }

        } 
        else 
        {

            // User does not exist
            $_SESSION['message_login'] = "Email does not exist! Please enter a valid email or register.";
            header('Location: ../form/login.html');
            exit();

        }

    }

?>