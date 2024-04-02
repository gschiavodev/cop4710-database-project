<?php

    // Include the user.php, university.php, rso.php, and user_in_rso.php files
    include_once "/var/www/html/php/user.php";
    include_once "/var/www/html/php/university.php";
    include_once "/var/www/html/php/rso.php";
    include_once "/var/www/html/php/user_in_rso.php";

    // Fetch the sample data from the rsos.json file
    $json_data = file_get_contents("./init/json/rsos.json");
    $sample_data = json_decode($json_data, true);

    // Loop through the sample data
    foreach ($sample_data as $rso)
    {

        // Get the RSO information
        $rso_name = $rso["name"];
        $rso_university_domain = $rso["university_domain"];
        $rso_description = $rso["description"];
        $rso_admin_id = $rso["admin_id"];
        $rso_members = $rso["members"];

        // Create list of member IDs
        $member_ids = array();

        // Loop through the members
        foreach ($rso_members as $member)
        {

            // Get the member information
            $member_email = $member["university_email"];

            // Get the user by university email
            $user = get_user_by_university_email($member_email);

            // Check if the user exists
            if ($user == null)
            {
    
                // User does not exist, output an error message and exit
                echo "CREATE_SAMPLE_RSO: User with university email " . $member_email . " does not exist. Exiting...\n\n";
                exit(1);

            }

            // Add the user ID to the list of member IDs
            array_push($member_ids, $user["id"]);

        }

        // Get the university ID by domain
        $university = get_university_by_email_domain($rso_university_domain);

        // Check if the university exists
        if ($university == null)
        {

            // University does not exist, output an error message and exit
            echo "CREATE_SAMPLE_RSO: University with domain " . $rso_university_domain . " does not exist\n\n";
            exit(1);

        }

        // Get the university ID
        $rso_university_id = $university["id"];

        // Create the RSO (first member is the admin)
        $rso_id = create_rso($rso_name, $rso_description, $rso_university_id, $member_ids);

        // Check if the RSO was created successfully
        if ($rso_id == null)
        {

            // RSO was not created successfully, output an error message and exit
            echo "CREATE_SAMPLE_RSO: Failed to create RSO: " . $rso_name . "\n\n";
            exit(1);

        }
        else
        {

            // RSO was created successfully, output a success message
            echo "CREATE_SAMPLE_RSO: RSO created successfully: " . $rso_name . "\n\n";

        }

    }

?>