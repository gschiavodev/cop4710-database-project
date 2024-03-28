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
            echo "Error: " . $user_added->error;
        }
        
    }
    else 
    {
        echo "Please fill in all the fields.<br>";
    }

?>