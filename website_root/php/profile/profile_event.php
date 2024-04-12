<?php

    // Start the session
    session_start();

    // Get the event ID from the URL
    $event_id = $_GET['event_id'];

    // Include the event.php file to get the event
    include_once "../event.php";

    // Get the event by id
    $event = get_event_by_id($event_id);

    // Check if the event was found
    if (!$event)
    {

        // Redirect to the events page
        header("Location: ../../index.html");
        exit();

    }

    // Check if a super admin, if not, they may not have access to the event 
    if (!$_SESSION['user_is_super_admin'])
    {

        // Inlcude necessary files
        include_once "../private_event.php";
        include_once "../rso_event.php";

        // Get private event data if available
        $private_event = get_private_event_by_event_id($event_id);
        $rso_event = get_rso_event_by_event_id($event_id);

        // Check if the event is a private event
        if ($private_event)
        {

            // Get the RSO ID
            $rso_id = $private_event['rso_id'];

            // Include the university.php file to get the university
            include_once "../university.php";

            // Get the university by RSO ID
            $university = get_university_by_rso_id($rso_id);

            // Check if the university was found
            if (!$university)
            {

                // Redirect to the universities page
                header("Location: ../../universities.html");
                exit();

            }

            // Get the user by email
            $user_university = get_university_by_university_email($_SESSION['user_university_email']);

            // Check if the user belongs to the university
            if ($user_university['id'] != $university['id'])
            {

                // Redirect to the universities page
                header("Location: ../../universities.html");
                exit();

            }

        }

        // Check if the event is an RSO event
        if ($rso_event)
        {

            // Get the RSO ID
            $rso_id = $rso_event['rso_id'];

            // Include necessary files
            include_once "../user_in_rso.php";

            // Check if the user is part of the RSO
            if (!check_if_user_in_rso_by_rso_id_and_user_id($rso_id, $_SESSION['user_id']))
            {

                // Redirect to the rsos page
                header("Location: ../../rsos.html");
                exit();

            }

        }

    }

    // Load the global configuration file
    require "../data/config.php";
    
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <title>College Event Website</title>
        <link rel="stylesheet" type="text/css" href="../../css/common_styles.css">
        <link rel="stylesheet" type="text/css" href="../../css/form_styles.css">
        <link rel="stylesheet" type="text/css" href="../../css/map_styles.css">

    </head>

    <body>
     
        <header>
            <nav>
                <ul>

                    <li><a href="../../index.html">Home</a></li>
                    <li><a href="../../universities.html">Universities</a></li>
                    <li><a href="../../rsos.html">RSOs</a></li>

                </ul>
                <ul>

                    <?php if (isset($_SESSION['user_university_email'])): ?>

                        <?php if (($_SESSION['user_is_admin']) || ($_SESSION['user_is_super_admin'])): ?>

                            <!-- This content will only be shown if the user is an admin -->
                            <li><a href="../../admin.html">Admin</a></li>
                            
                        <?php endif; ?>

                        <!-- This content will only be shown if the user is logged in -->
                        <li><a href="../../index.html?logout=true">Logout</a></li>

                    <?php else: ?>

                        <li><a href="../form/login.html">Login</a></li>
                        <li><a href="../form/register.html">Register</a></li>

                    <?php endif; ?>

                </ul>
            </nav>
        </header>
        
        <main>

            <div class="row">

                <section>
                    <h1><?php echo $event['name']; ?></h1>
                    <p><?php echo $event['description']; ?></p>
                </section>
                
            </div>

            <div class="row">
                    
                    <section>
                        <h2>Event Details</h2>
                        <p>Date: <?php echo $event['date']; ?></p>
                        <p>Time: <?php echo $event['time']; ?></p>
                        <p>Category: <?php echo $event['category']; ?></p>
                        <p>Contact Email: <?php echo $event['email']; ?></p>
                        <p>Contact Phone Number: <?php echo $event['phone_number']; ?></p>
                    </section>

            </div>

            <div class="row">
                    
                    <?php

                        // Include necessary files
                        include_once "../location.php";

                        // Get the event location
                        $location_id = $event['location_id'];

                        // Get the location by ID
                        $location = get_location_by_id($location_id);

                        // Check if the location was found
                        if (!$location)
                        {

                            // Redirect to the events page
                            header("Location: ../../index.html");
                            exit();

                        }

                        // Get the location information
                        $address_line_01 = $location['address_line_01'];
                        $address_line_02 = $location['address_line_02'];
                        $city = $location['city'];
                        $state = $location['state'];
                        $zip_code = $location['zip_code'];

                        // Check if address line 2 is empty
                        $address_line_02 = ($address_line_02) ? $address_line_02 : "N/A";

                        // Prepare the address for the Google Maps API
                        $address = urlencode($address_line_01 . ' ' . $address_line_02 . ' ' . $city . ' ' . $state . ' ' . $zip_code);

                    ?>

                    <section>
                        <h2>Event Location</h2>
                        <p>Address Line 1: <?php echo $address_line_01; ?></p>
                        <p>Address Line 2: <?php echo $address_line_02; ?></p>
                        <p>City: <?php echo $city; ?></p>
                        <p>State: <?php echo $state; ?></p>
                        <p>Zip Code: <?php echo $zip_code; ?></p>
                    </section>
                    
                    <?php if ($google_api_key): ?>
                        <section>

                            <div id="map"></div>
                            <script>window.google_api_key = "<?= $google_api_key ?>";</script>
                            <script>window.address = "<?= $address ?>";</script>
                            <script src="../../javascript/map.js"></script>

                        </section>
                    <?php endif; ?>

            </div>

            <div class="row">
                    
                <section>
                    <h2>Event Comments</h2>
                    <?php if (isset($_SESSION['user_university_email'])): ?>
                        <p>Write a comment, or view comments from others.</p>
                    <?php else: ?>
                        <p>Login to write a comment, or view comments from others.</p>
                    <?php endif; ?>
                </section>

            </div>

            <div class="row">
                    
                <?php

                    // TODO: Display comments

                ?>

                <section>
                    
                    <!-- Comment Form -->
                    <form action="../comment/create_comment.php" method="post">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                        <textarea name="comment" placeholder="Write a comment..."></textarea>
                        <div class="star-rating">
                            <input type="hidden" name="rating" id="rating">
                            <span class="star" data-value="1">&#9733;</span>
                            <span class="star" data-value="2">&#9733;</span>
                            <span class="star" data-value="3">&#9733;</span>
                            <span class="star" data-value="4">&#9733;</span>
                            <span class="star" data-value="5">&#9733;</span>
                        </div>
                        <button type="submit">Add Comment</button>
                    </form>

                    <!-- Link your JavaScript file here -->
                    <script src="../../javascript/stars.js"></script>

                </section>

            </div>

        </main>

    </body>

</html>