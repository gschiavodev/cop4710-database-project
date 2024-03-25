<!DOCTYPE html>

<?php

    $servername = "mysql";
    $username = "root";
    $password = "password";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) 
    {
        die("Connection failed: " . mysqli_connect_error());
    }

    echo "Connected successfully";

?>

<html>

    <head>
        <title>College Event Website</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>

    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="rsos.php">RSOs</a></li>
                </ul>
                <ul>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
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