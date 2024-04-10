<?php

    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_university_email']))
    {

        // Redirect to the login page
        header("Location: ../form/login.html");

        // Exit the script
        exit();

    }

    // Include the database connection file
    include_once "../database.php";

    // Get the data from the form
    $rso_name = $_POST['rso_name'];
    $rso_description = $_POST['rso_description'];
    
    // Initialize the array of user emails
    $emails = array();

    // Get the list of emails of the users from the form
    for ($i = 1; isset($_POST['student_email_' . $i]); $i++)
        array_push($emails, $_POST['student_email_' . $i]);

    // Check that the user has entered at least three emails (size of the $emails array is 3)
    if (count($emails) < 3)
    {

        // Redirect to the RSOs page with an error message
        $_SESSION['message_rsos'] = "You must enter at least three student emails.";
        header("Location: ../../rsos.html");

        // Exit the script
        exit();

    }    

    // Include the university.php file
    include_once "../university.php";

    // Get the university ID of the user who submitted the form
    $university_id = get_university_by_university_email($_SESSION['user_university_email'])['id'];

    // Initialize the array of user IDs
    $user_ids = array();

    // Include the user.php file
    include_once "../user.php";

    // Get the user IDs of the users from the database
    foreach ($emails as $email)
    {

        // Check if the user exists
        if (get_user_id_by_university_email($email) == null)
        {

            // Redirect to the RSOs page with an error message
            $_SESSION['message_rsos'] = "One or more of the student emails are not valid.";
            header("Location: ../../rsos.html");

            // Exit the script
            exit();

        }

        // Check if the user has belongs to the same university as the user who submitted the form
        if (get_university_by_university_email($email)['id'] != $university_id)
        {

            // Redirect to the RSOs page with an error message
            $_SESSION['message_rsos'] = "All students must belong to the same university.";
            header("Location: ../../rsos.html");

            // Exit the script
            exit();

        }

        // Get the user ID of the user
        $user_id = get_user_id_by_university_email($email);

        // Add the user ID to the array
        array_push($user_ids, $user_id);

    }

    // Add the user ID of the user who submitted the form to the array
    array_unshift($user_ids, $_SESSION['user_id']);

    // Include the rso.php file
    include_once "../rso.php";

    // Create the RSO
    $rso_id = create_rso($rso_name, $rso_description, $university_id, $user_ids);

    // Check if the RSO was created
    if ($rso_id == null)
    {

        // Redirect to the RSOs page wit an error message
        $_SESSION['message_rsos'] = "The RSO could not be created.";
        header("Location: ../../rsos.html");

        // Exit the script
        exit();

    }
    else
    {

        // Redirect to the RSOs page with a success message
        $_SESSION['message_rsos'] = "The RSO was created successfully. Please wait for approval.";
        header("Location: ../../rsos.html");

        // Exit the script
        exit();

    }


?>