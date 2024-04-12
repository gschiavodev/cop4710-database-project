<?php

    session_start();

    // Get the rso ID from the URL
    $rso_id = $_GET['rso_id'];

    // Include the rso.php file to get the rso
    include_once "../rso.php";

    // Get the rso by id
    $rso = get_rso_by_id($rso_id);

    // Check if the rso was found
    if (!$rso)
    {

        // Redirect to the rsos page
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

                // Includes
                include_once "../rso.php";
                include_once "../university.php";
                include_once "../user_in_rso.php";
                include_once "../private_event.php";
                include_once "../rso_event.php";
                
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
                    if (($index == 0) && !$_SESSION['user_is_super_admin'] && ($rso['university_id'] != get_university_by_university_email($_SESSION['user_university_email'])['id']))
                        continue;

                    // If checking for organization events, and user is not part of the RSO, skip
                    if (($index == 1) && !$_SESSION['user_is_super_admin'] && (!check_if_user_in_rso_by_rso_id_and_user_id($rso_id, $_SESSION['user_id'])))
                        continue;

                    // Get the title of the events
                    $title = $keys[$index];

                    // 
                    echo "<div class='row'>";
                    echo "<section>";
                    echo "<h2>" . $title . "</h2>";
                    echo "</section>";
                    echo "</div>";

                    // Constants
                    $EVENTS_PER_ROW = 3;

                    // Check if there are any events
                    if ($event_data->num_rows > 0)
                    {

                        // Loop through the events
                        for ($i = 0; $i < $event_data->num_rows; $i++)
                        {

                            // Get the event
                            $event = $event_data->fetch_assoc();

                            // Get the event data
                            $event_id = $event['id'];
                            $event_name = $event['name'];
                            $event_category = $event['category'];
                            $event_description = $event['description'];
                            $event_date = $event['date'];
                            $event_start_time = $event['start_time'];
                            $event_end_time = $event['end_time'];
                            $phone_number = $event['phone_number'];
                            $email = $event['email'];

                            // TODO: Get the location of the event

                            // Open a new row after every three event sections
                            if ($i % $EVENTS_PER_ROW == 0)
                                echo "<div class='row'>";

                            echo "<section>";

                            // Display the event
                            echo "<h3>" . $event_name . "</h3>";
                            echo "<p>Category: " . $event_category . "</p>";
                            echo "<p>Description: " . $event_description . "</p>";
                            echo "<p>Date: " . date("F j, Y", strtotime($event_date)) . "</p>";
                            echo "<p>Starting Time: " . date("g:i a", strtotime($event_start_time)) . "</p>";
                            echo "<p>Ending Time: " . date("g:i a", strtotime($event_end_time)) . "</p>";
                            echo "<p>Phone Number: " . $phone_number . "</p>";
                            echo "<p>Email: " . $email . "</p>";
                            
                            // TODO: Display the location of the event

                            // Create a button to view the event details
                            echo "<form action=\"profile_event.php\" method=\"get\">";
                            echo "<input type=\"hidden\" name=\"event_id\" value=\"" . $event_id . "\">";
                            echo "<button style='margin-top: 0px' type=\"submit\">View Event</button>";
                            echo "</form>";

                            echo "</section>";

                            // Close the row after every three event sections
                            if ($i % $EVENTS_PER_ROW == ($EVENTS_PER_ROW - 1) || $i == $event_data->num_rows - 1)
                            {

                                // If last row has less than three RSOs, add empty sections to fill the row
                                for ($j = 0; $j < $EVENTS_PER_ROW - ($i % $EVENTS_PER_ROW) - 1; $j++)
                                    echo "<section><p></p></section>";

                                // Close the row
                                echo "</div>";
                            }
                            
                        }

                    }
                    else
                    {

                        // Display a message if there are no events
                        echo "<div class='row'>";
                        echo "<section>";
                        echo "<p>There are no events to display.</p>";
                        echo "</section>";
                        echo "</div>";

                    }

                }

            ?>

            <?php

                // Check if the user is the admin of the RSO
                if ($rso['admin_id'] == $_SESSION['user_id'])
                {

                    // Display the create event form
                    echo "<div class='row'>";
                    echo "<section>";
                    echo "<h2>Create Event</h2>";
                    echo "<p>Please use the following page to create an event for your RSO.</p>";
                   
                    // Create a button to view the create event form
                    echo "<form action=\"../form/create_event.html\" method=\"get\">";
                    echo "<input type=\"hidden\" name=\"event_type\" value=\"private_or_rso_event\">";
                    echo "<input type=\"hidden\" name=\"rso_id\" value=\"" . $rso['id'] . "\">";
                    echo "<button style='margin-top: 0px' type=\"submit\">Create Event Form</button>";
                    echo "</form>";

                    echo "</section>";
                    echo "</div>";

                }

            ?>

        </main>

    </body>

</html>