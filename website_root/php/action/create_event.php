<?php

    // Start session
    session_start();

    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["user_id"]))
    {
        
        // Redirect to login page
        header("location: ../form/login.html");
        exit();

    }

    // Get the event type
    $event_type = $_POST["event_type"];

    // Get the POST data
    $event_name = $_POST["event_name"];
    $event_description = $_POST["event_description"];
    $event_category = $_POST["event_category"];
    $event_email = $_POST["event_email"];
    $event_phone_number = $_POST["event_phone_number"];
    $event_date = $_POST["event_date"];
    $event_start_time = $_POST["event_start_time"];
    $event_end_time = $_POST["event_end_time"];
    $location_name = $_POST["location_name"];
    $event_address_line_01 = $_POST["address_line_01"];
    $event_address_line_02 = $_POST["address_line_02"];
    $event_city = $_POST["city"];
    $event_state = $_POST["state"];
    $event_zip_code = $_POST["zip_code"];

    // Check if the POST data is set, otherwise redirect to the create event page
    if (!isset($event_name) || !isset($event_description) || !isset($event_category) || !isset($event_phone_number) || !isset($event_date) || !isset($event_start_time) || !isset($event_end_time) || !isset($location_name) || !isset($event_address_line_01) || !isset($event_city) || !isset($event_state) || !isset($event_zip_code))
    {

        // Redirect to the create event page
        $_SESSION["message_create_event"] = "Please fill out all the fields.";
        header("location: ../form/create_event.html");
        exit();

    }

    // Validate the email if it is set
    if (isset($event_email) && ($event_email != "") && !filter_var($event_email, FILTER_VALIDATE_EMAIL))
    {

        // Redirect to the create event page
        $_SESSION["message_create_event"] = "Please enter a valid email address.";
        header("location: ../form/create_event.html");
        exit();

    }

    // If the email is not set, use the user's email
    if (!isset($event_email) || $event_email == "")
    {

        // Set the email to the user's email
        $event_email = $_SESSION["user_university_email"];

    }

    // Validate the phone number if it is set
    if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $event_phone_number))
    {

        // Redirect to the create event page
        $_SESSION["message_create_event"] = "Please enter a valid phone number. Use the format: 123-456-7890.";
        header("location: ../form/create_event.html");
        exit();

    }

    // Include the location.php file
    include_once "../location.php";

    // Check if the location of the event already exists, if so use the existing location
    $location = get_location_by_full_address($event_address_line_01, $event_address_line_02, $event_city, $event_state, $event_zip_code);

    // Check if the location exists
    if ($location === null)
    {

        // Create the location
        $location_id = create_location($location_name, $event_address_line_01, $event_address_line_02, $event_city, $event_state, $event_zip_code);

        // Check if the location was created
        if ($location_id === null)
        {

            // Redirect to the create event page
            $_SESSION["message_create_event"] = "Failed to create the location for the event. Please try again.";
            header("location: ../form/create_event.html");
            exit();

        }

    }
    else
    {

        // Get the location ID
        $location_id = $location["id"];

    }

    // Include the event.php file
    include_once "../event.php";

    // Create the event
    $event_id = create_event($event_name, $event_description, $event_category, $event_email, $event_phone_number, $event_date, $event_start_time, $event_end_time, $location_id);

    // Check if the event was created
    if ($event_id === null)
    {

        // Redirect to the create event page
        $_SESSION["message_create_event"] = "Failed to create the event. Please try again.";
        header("location: ../form/create_event.html");
        exit();

    }

    // Check event type
    switch ($event_type)
    {

        case "public_event":
        {

            // Include the public_event.php file
            include_once "../public_event.php";

            // Create the public event
            $public_event_id = create_public_event_by_event_id($event_id);

            // Check if the public event was created
            if ($public_event_id === null)
            {

                // Delete the event
                delete_event_by_event_id($event_id);

                // Redirect to the create event page
                $_SESSION["message_create_event"] = "Failed to request the public event. Please try again.";
                header("location: ../form/create_event.html");
                exit();

            }

            // Redirect to the event form with success message
            $_SESSION["message_create_event"] = "The public event was requested successfully.";
            header("location: ../form/create_event.html");
            exit();

            break;
        
        }

        case "private_event":
        {

            // Get the RSO ID
            $rso_id = $_POST["rso_id"];

            // Check if the RSO ID is set
            if (!isset($rso_id))
            {

                // Delete the event
                delete_event_by_event_id($event_id);

                // Redirect to the create event page
                $_SESSION["message_create_event"] = "1 Failed to create the private event. Please try again.";
                header("location: ../form/create_event.html");
                exit();

            }

            // Include the private_event.php file
            include_once "../private_event.php";

            // Create the private event
            $private_event_id = create_private_event_by_event_and_rso_id($event_id, $rso_id);

            // Check if the private event was created
            if ($private_event_id === null)
            {

                // Delete the event
                delete_event_by_event_id($event_id);

                // Redirect to the create event page
                $_SESSION["message_create_event"] = "2 Failed to create the private event. Please try again.";
                header("location: ../form/create_event.html");
                exit();

            }

            // Redirect to the event form with success message
            $_SESSION["message_create_event"] = "The private event was created successfully.";
            header("location: ../form/create_event.html");
            exit();

            break;

        }

        case "rso_event":
        {

            // Get the RSO ID
            $rso_id = $_POST["rso_id"];

            // Check if the RSO ID is set
            if (!isset($rso_id))
            {

                // Delete the event
                delete_event_by_event_id($event_id);

                // Redirect to the create event page
                $_SESSION["message_create_event"] = "Failed to create the RSO event. Please try again.";
                header("location: ../form/create_event.html");
                exit();

            }

            // Include the rso_event.php file
            include_once "../rso_event.php";

            // Create the RSO event
            $rso_event_id = create_rso_event_by_event_and_rso_id($event_id, $rso_id);

            // Check if the RSO event was created
            if ($rso_event_id === null)
            {

                // Delete the event
                delete_event_by_event_id($event_id);

                // Redirect to the create event page
                $_SESSION["message_create_event"] = "Failed to create the RSO event. Please try again.";
                header("location: ../form/create_event.html");
                exit();

            }

            // Redirect to the event form with success message
            $_SESSION["message_create_event"] = "The event was created successfully.";
            header("location: ../form/create_event.html");
            exit();


            break;

        }

        default:
        {

            // Delete the event
            delete_event_by_event_id($event_id);

            // Redirect to the create event page
            $_SESSION["message_create_event"] = "Failed to create the event. Please try again.";
            header("location: ../form/create_event.html");
            exit();
        
        }

    }

?>