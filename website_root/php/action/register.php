<?php

    session_start();

    // Check if the username and password fields are set
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['university_email']) && isset($_POST['password'])) 
    {

        // Gather the user information from the form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $university_email = $_POST['university_email'];
        $password = $_POST['password'];

        // Validate the email first
        if (!filter_var($university_email, FILTER_VALIDATE_EMAIL)) 
        {

            // Email is not valid
            $_SESSION['message_register'] = "Invalid email address!";
            header('Location: ../form/register.html');
            exit();

        }

        // Make sure the email is lowercase
        $university_email = strtolower($university_email);

        // Include the university.php file to get the university id
        include_once "../university.php";

        // Get the university id
        $university_id = get_university_by_university_email($university_email);

        // Check if the university id is set
        if (!$university_id)
        {

            // University not found
            $_SESSION['message_register'] = "No university registered for provided email domain!";
            header('Location: ../form/register.html');
            exit();

        }

        // Include the user.php file to insert the new user
        include_once "../user.php";

        // Attempt to add the user
        $success = create_user($first_name, $last_name, $university_email, $password);

        // Check if the user was added successfully
        if ($success)
        {

            // Include the user.php file to get the user by university email
            include_once "../user.php";

            // Get the user by university email
            $user = get_user_by_university_email($university_email);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_university_email'] = $user['university_email'];

            // Redirect to index.html
            header('Location: ../../index.html');
            exit();

        } 
        else 
        {

            // User was not added successfully
            $_SESSION['message_register'] = "Failed to register!";
            header('Location: ../form/register.html');
            exit();

        }
        
    }
    else 
    {

        // All the fields are not set
        $_SESSION['message_register'] = "All fields are required!";
        header('Location: ../.form/register.html');
        exit();

    }

?>