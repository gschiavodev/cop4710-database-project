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

    // Check if the location already exists (if the location exists, use the existing location)
    if ($result->num_rows > 0) 
    {

        // Get the location information
        $location = $result->fetch_assoc();
        
    } 
    else 
    {

        // Include the location.php file
        include_once "../location.php";

        // Create the location
        $location_created_result = create_location($university_name, $address_line_01, $address_line_02, $city, $state, $zip_code, $latitude, $longitude);

        // Check if the location was created
        if ($location_created_result) 
        {

            // Get the location information
            $location = get_location_by_id($location_created_result);

        } 
        else 
        {

            // Redirect to the admin page
            $_SESSION['message'] = "Failed to create the location.";
            header('Location: ../../admin.html');
            exit();

        }


    }

    // Include the university.php file
    include_once "../university.php";

    // Create the university
    $university_created_result = create_university($university_name, $description, $email_domain, $location['id']);

    // Check if the university was created
    if ($university_created_result) 
    {

        // Redirect to the admin page
        $_SESSION['message'] = "University created successfully.";
        header('Location: ../../admin.html');
        exit();

    } 
    else 
    {

        // Redirect to the admin page
        $_SESSION['message'] = "Failed to create the university.";
        header('Location: ../../admin.html');
        exit();

    }

?>
