<?php
    
    session_start();

    // Check if the logout link was clicked
    if (isset($_GET['logout'])) 
    {

        // End the session
        session_destroy();

        // Redirect to the login page
        header('Location: index.php');
        exit();

    }

    $servername = "mysql";
    $username = "root";
    $password = "password";
    $database = "college_events";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }

?>

<!DOCTYPE html>

<html>

    <head>
        <title>College Event Website</title>
        <link rel="stylesheet" type="text/css" href="css-code/common_styles.css">
    </head>

    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php">Events</a></li>
                    <li><a href="index.php">RSOs</a></li>
                </ul>
                <ul>
                    <?php if (isset($_SESSION['username'])) :?>
                        <!-- This content will only be shown if the user is logged in -->
                        <li><a href="index.php?logout=true">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>
        <main>

            <div class="row">

                <section class="section">
                    <h1>College Event Website</h1>
                    <p>Welcome to the College Event Website! This platform is designed to help students keep track of events happening around campus. Whether you're looking for social events, fundraising activities, or tech talks, you'll find all the information you need right here. Register to start exploring events, join RSOs, and more!</p>
                </section>
                
            </div>

            <div class="row">

                <section class="section">
                    <h2>Section 1</h2>
                    <p>Section 1 content goes here.</p>
                </section>

                <section class="section">
                    <h2>Section 2</h2>
                    <p>Section 2 content goes here.</p>
                </section>

            </div>

        </main>

        <footer>
            <p>&copy; Gabriel Schiavo</p>
        </footer>
        
    </body>
</html>