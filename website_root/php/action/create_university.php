<?php

    session_start();

    // Check if the user is a super admin
    if (!$_SESSION['user_is_super_admin']) 
    {

        // User is not a super admin, redirect to index.html
        header('Location: ../../index.html');
        exit();

    }
    
    // Get the university information from the form
    $university_name = $_POST['university_name'];
    $description = $_POST['description'];
    $email_domain = $_POST['email_domain'];
    $num_students = $_POST['num_students'];
    $location_name = $_POST['location_name'];
    $address_line_01 = $_POST['address_line_01'];
    $address_line_02 = $_POST['address_line_02'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;

    // Include the location.php file
    include_once "../location.php";

    // Get the location information
    $location = get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);

    // Check if the location exists
    if (!$location) 
    {
;
        // Create the location
        $success = create_location($location_name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);

        // Check if create_location returned an string
        if (is_string($success)) 
        {

            // Redirect to the admin page
            $_SESSION['message_create_university'] = $success;
            header('Location: ../../admin.html#section_create_university');
            exit();

        }

        // Check if the location was created
        if ($success) 
        {

            // Get the location information
            $location = get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);

        } 
        else 
        {

            // Redirect to the admin page
            $_SESSION['message_create_university'] = "Failed to create the location.";
            header('Location: ../../admin.html#section_create_university');
            exit();

        }

    }

    // Include the university.php file
    include_once "../university.php";

    // Check if the university already exists
    $university = get_university_by_email_domain($email_domain);

    // Check if the university exists
    if ($university) 
    {

        // Redirect to the admin page
        $_SESSION['message_create_university'] = "University with the email domain already exists.";
        header('Location: ../../admin.html#section_create_university');
        exit();

    }

    // Create the university
    $university_created_result = create_university($university_name, $description, $email_domain, $location['id'], $num_students);

    // Check if the university was created
    if ($university_created_result) 
    {

        // Redirect to the admin page
        $_SESSION['message_create_university'] = "University created successfully.";
        header('Location: ../../admin.html#section_create_university');
        exit();

    } 
    else 
    {

        // Redirect to the admin page
        $_SESSION['message_create_university'] = "Failed to create the university.";
        header('Location: ../../admin.html#section_create_university');
        exit();

    }

?>
