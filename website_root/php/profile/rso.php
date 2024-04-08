<?php

    session_start();

    // Get the rso ID from the URL
    $rso_id = $_GET['rso_id'];

    // Include the university.php file to get the university
    include_once "../rso.php";

    // Get the university by id
    $rso = get_rso_by_id($rso_id);

    // Check if the university was found
    if (!$rso)
    {

        // Redirect to the universities page
        header("Location: ../../rsos.html");
        exit();

    }

?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <title>College Event Website</title>
        <link rel="stylesheet" type="text/css" href="../../css/common_styles.css">
        <link rel="stylesheet" type="text/css" href="../../css/form_styles.css">
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
                    <h1><?php echo $rso['name']; ?></h1>
                    <p><?php echo $rso['description']; ?></p>
                </section>
                
            </div>

            <?php

                // Include the event.php, rso.php, and university.php files
                include_once "../event.php";
                include_once "../rso.php";
                include_once "../university.php";
                
                // Get the events
                $events = array
                (
                    "University Events" => get_private_events_by_rso_id($rso_id),
                    "Organization Events" => get_rso_events_by_rso_id($rso_id)
                );
                
                // Get the keys and values of the events
                $keys = array_keys($events);
                $values = array_values($events);
                
                // Loop through the events
                foreach ($values as $index => $event_data) 
                {

                    // If checking for university events, and user is not from the university, skip
                    if (($index == 0) && ($rso['university_id'] != get_university_by_email($_SESSION['user_university_email'])['id']))
                        continue;

                    // If checking for organization events, and user is not part of the RSO, skip
                    if (($index == 1) && (!check_if_user_in_rso_by_rso_id_and_user_id($rso_id, $_SESSION['user_id'])))
                        continue;

                    // Get the title of the events
                    $title = $keys[$index];

                    // 
                    echo "<div class='row'>";
                    echo "<section>";
                    echo "<h2>" . $title . "</h2>";
                    echo "</section>";
                    echo "</div>";

                    echo "<div class='row'>";
                    echo "<section>";

                    // Check if there are any events
                    if ($event_data->num_rows > 0)
                    {

                        // Loop through the events
                        while ($event = $event_data->fetch_assoc())
                        {

                            // Get the event data
                            $event_name = $event['name'];
                            $event_category = $event['category'];
                            $event_description = $event['description'];
                            $event_date = $event['date'];
                            $event_time = $event['time'];
                            $phone_number = $event['phone_number'];
                            $email = $event['email'];

                            // TODO: Get the location of the event

                            // Display the event
                            echo "<div class='event'>";
                            echo "<h3>" . $event_name . "</h3>";
                            echo "<p>" . $event_description . "</p>";
                            echo "<p>" . $event_date . " at " . $event_time . "</p>";
                            echo "<p>" . $phone_number . "</p>";
                            echo "<p>" . $email . "</p>";
                            echo "</div>";

                        }

                    }
                    else
                    {

                        // Display a message if there are no events
                        echo "<p>There are no events to display.</p>";

                    }

                    echo "</section>";
                    echo "</div>";

                }

            ?>

        </main>

    </body>

</html>