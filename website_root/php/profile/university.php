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

                        <li><a href="../../login.html">Login</a></li>
                        <li><a href="../../register.html">Register</a></li>

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

            <!-- Rest of the page -->

        </main>

    </body>

</html>