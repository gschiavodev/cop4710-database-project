<?php

    session_start();

    // Check if the user is already logged in
    if (isset($_SESSION['username'])) 
    {
        
        // User is already logged in, redirect to index.php
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {

        if (isset($_POST['username']) && isset($_POST['password'])) 
        {

            // Assuming $conn is your MySQLi connection
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare an INSERT statement to add the new user to the database
            $statement = $conn->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
            $statement->bind_param("ss", $username, $hashed_password);
            $statement->execute();

            if ($statement->affected_rows === 1) 
            {
                // Start the session and set session variables
                $_SESSION['username'] = $username;

                // Redirect to index.php
                header('Location: index.php');
                exit();
            } 
            else 
            {
                echo "Error: " . $statement->error;
            }
            
        } 
        else 
        {
            echo "Please fill in both fields.<br>";
        }

    }
?>

<!DOCTYPE html>

<html>

    <head>
        <title>Register</title>
        <link rel="stylesheet" type="text/css" href="css-code/common_styles.css">
        <link rel="stylesheet" type="text/css" href="css-code/form_styles.css">
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
                <div class="section">
                    <h2>Register</h2>
                </div>
            </div>

            <div class="row">
                <div class="section">

                    <p>Please enter a username and password to register.</p>

                    <form method="POST" action="register.php" class="login-form">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required>
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                        <input type="submit" value="Register">
                    </form> 

                </div>
            </div>
        </main>

        <footer>
            <p>&copy; Gabriel Schiavo</p>
        </footer>

    </body>

</html>