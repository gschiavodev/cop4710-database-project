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
            $_SESSION['message'] = "Invalid email address!";
            header('Location: ../../register.html');
            exit();

        }

        // Include the university.php file to get the university id
        include_once "../university.php";

        // Get the university id
        $university_id = get_university_by_email($university_email);

        // Check if the university id is set
        if (!$university_id)
        {

            // University not found
            $_SESSION['message'] = "No university registered for provided email domain!";
            header('Location: ../../register.html');
            exit();

        }

        // Include the user.php file to insert the new user
        include_once "../user.php";

        // Attempt to add the user
        $user_added = insert_new_user($first_name, $last_name, $university_email, $password);

        // Check if the user was added successfully
        if ($user_added->affected_rows === 1)
        {

            // Set session variables
            $_SESSION['user_id'] = $user_added->insert_id;
            $_SESSION['user_university_email'] = $university_email;

            // Redirect to index.html
            header('Location: ../../index.html');
            exit();

        } 
        else 
        {

            // User was not added successfully
            $_SESSION['message'] = "Failed to register!";
            header('Location: ../../register.html');
            exit();

        }
        
    }
    else 
    {

        // All the fields are not set
        $_SESSION['message'] = "All fields are required!";
        header('Location: ../../register.html');
        exit();

    }

?>