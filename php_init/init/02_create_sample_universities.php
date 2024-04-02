<?php

    // Include the location.php, and university.php files
    include_once "/var/www/html/php/location.php";
    include_once "/var/www/html/php/university.php";

    // Fetch the sample data from the universities.json file
    $json_data = file_get_contents("./init/json/universities.json");
    $sample_data = json_decode($json_data, true);

    // Loop through the sample data and insert each university into the database
    foreach ($sample_data as $university_data) 
    {

        // Get the university data
        $university_name = $university_data["name"];
        $description = $university_data["description"];
        $email_domain = $university_data["email_domain"];
        $num_students = $university_data["num_students"];
        $location = $university_data["location"];

        // Get the location data
        $location_name = $location["name"];
        $address_line_01 = $location["address_line_01"];
        $address_line_02 = $location["address_line_02"];
        $city = $location["city"];
        $state = $location["state"];
        $zip_code = $location["zip_code"];
        $longitude = $location["longitude"];
        $latitude = $location["latitude"];

        // Insert the location
        $location = get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zip_code, $longitude, $latitude);

        // Check if the location exists
        if (!$location) 
        {

            // Create the location
            $success = create_location($location_name, $address_line_01, $address_line_02, $city, $state, $zip_code, $longitude, $latitude);

            // Check if create_location returned an string
            if (is_string($success)) 
            {

                // Print the error message
                echo "CREATE_SAMPLE_UNIVERSITY: Failed to create the location for {$university_name}: " . $success . "\n\n";
                exit(1);

            }

            // Check if the location was created
            if ($success) 
            {

                // Get the location information
                $location = get_location_by_full_address($address_line_01, $address_line_02, $city, $state, $zip_code, $longitude, $latitude);

            } 
            else 
            {

                // Print the error message
                echo "CREATE_SAMPLE_UNIVERSITY: Failed to create the location for {$university_name}.\n\n";
                exit(1);

            }

        }

        // Check if the university already exists
        $university = get_university_by_email_domain($email_domain);

        // Check if the university exists
        if ($university) 
        {

            // Print the error message
            echo "CREATE_SAMPLE_UNIVERSITY: University with the email domain {$email_domain} already exists.\n\n";
            continue;

        }

        // Insert the university
        $success = create_university($university_name, $description, $email_domain, $location['id'], $num_students);

        // Check if the university was created
        if (!$success) 
        {

            // Print the error message
            echo "CREATE_SAMPLE_UNIVERSITY: Failed to create {$university_name}.\n\n";
            exit(1);

        }

        // Print the success message
        echo "CREATE_SAMPLE_UNIVERSITY: {$university_name} created successfully!\n\n";

    }

?>