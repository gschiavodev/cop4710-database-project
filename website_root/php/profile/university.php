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

                // Get the university id of the user from the user email domain
                $user_university_id = get_university_by_email($_SESSION['user_university_email']);

                // Check if the user is part of the university
                if ($user_university_id == $university_id)
                {

                    // Include the event.php file to get the private events
                    include_once "../event.php";

                    // Get the private events by university id
                    $private_events = get_private_events_by_university_id($university_id);

                    // Check if there are private events
                    if ($private_events->num_rows > 0)
                    {

                        // Loop through each private event
                        while ($private_event = $private_events->fetch_assoc())
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

                            // Display the private event information
                            echo "<div class='row'>";
                            echo "<section>";
                            echo "<h2>$event_name</h2>";
                            echo "<p>$event_description</p>";
                            echo "<p>$event_date</p>";
                            echo "<p>$event_time</p>";
                            echo "<p>$phone_number</p>";
                            echo "<p>$email</p>";
                            echo "</section>";
                            echo "</div>";

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

            ?>

        </main>

    </body>

</html>