<?php

    session_start();

    // Get the university ID from the URL
    $university_id = $_GET['university_id'];

    // Include the university.php file to get the university
    include_once "../university.php";

    // Get the university by id
    $university = get_university_by_id($university_id);

    // Check if the university was found
    if (!$university)
    {

        // Redirect to the universities page
        header("Location: ../../universities.html");
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
                    <h1><?php echo $university['name']; ?></h1>
                    <p><?php echo $university['description']; ?></p>
                </section>
                
            </div>

            <?php

                // Private events title
                echo "<div class='row'>";
                echo "<section>";
                echo "<h2>Private events</h2>";
                echo "</section>";
                echo "</div>";

                // Include the university.php file to get the university id by university email
                include_once "../university.php";

                // Get the university id of the user from the user email domain
                $user_university_id = get_university_by_university_email($_SESSION['user_university_email'])['id'];

                // Check if the user is part of the university
                if ($user_university_id == $university_id)
                {

                    // Includes
                    include_once "../private_event.php";

                    // Get the private events by university id
                    $private_events = get_private_events_by_university_id($university_id);

                    // Check if there are private events
                    if ($private_events->num_rows > 0)
                    {

                        // Loop through each private event
                        while ($private_event = $private_events->fetch_assoc())
                        {

                            // Get the RSO hosting the event
                            $rso_id = $private_event['rso_id'];
                            $event_id = $private_event['id'];

                            // Include the rso.php file
                            include_once "../rso.php";

                            // Get the RSO by ID
                            $rso = get_rso_from_private_event_id($event_id);

                            // Get the event data
                            $event_name = $private_event['name'];
                            $event_host = $rso['name'];
                            $event_category = $private_event['category'];
                            $event_description = $private_event['description'];
                            $event_date = $private_event['date'];
                            $event_time = $private_event['time'];
                            $phone_number = $private_event['phone_number'];
                            $email = $private_event['email'];

                            // TODO: Get the location of the event

                            // Display the private event information
                            echo "<div class='row'>";
                            echo "<section>";
                            echo "<h2>$event_name</h2>";
                            echo "<p>$event_host</p>";
                            echo "<p>$event_description</p>";
                            echo "<p>$event_date</p>";
                            echo "<p>$event_time</p>";
                            echo "<p>$phone_number</p>";
                            echo "<p>$email</p>";
                            echo "</section>";
                            echo "</div>";

                            // TODO: Display the location of the event

                        }

                    }
                    else
                    {

                        // Display a message if there are no private events
                        echo "<div class='row'>";
                        echo "<section>";
                        echo "<p>There are no private events for this university.</p>";
                        echo "</section>";
                        echo "</div>";

                    }

                }
                else
                {

                    // Display a message if the user is not part of the university
                    echo "<div class='row'>";
                    echo "<section>";
                    echo "<p>You are not part of this university.</p>";
                    echo "</section>";
                    echo "</div>";

                }

            ?>

        </main>

    </body>

</html>