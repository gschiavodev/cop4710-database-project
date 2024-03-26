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

    // Check if the form was submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {

        if (isset($_POST['username']) && isset($_POST['password']))
        {

            // The form was submitted, let's try to log the user in
            // Assuming $conn is your MySQLi connection
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare a SELECT statement to get the user with the provided username
            $statement = $conn->prepare("SELECT * FROM user WHERE username = ?");
            $statement->bind_param("s", $username);
            $statement->execute();

            $result = $statement->get_result();
            $user = $result->fetch_assoc();

            if ($user) 
            {

                // User exists, now we check the password
                if (password_verify($password, $user['password'])) 
                {

                    // Password is correct, start the session
                    session_start();
                    $_SESSION['userId'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header('Location: index.php');
                    exit();

                } 
                else 
                {
                    // Password is incorrect
                    echo "Wrong password!<br>";
                }

            } 
            else 
            {

                // User does not exist
                echo "User does not exist!<br>";

            }

        }

    }

?>

<!DOCTYPE html>

<html>

    <head>
        <title>Login</title>
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
                    <h2>Login</h2>
                </div>
            </div>

            <div class="row">
                <div class="section">

                    <p>Please enter your username and password to login.</p>

                    <form method="POST" action="login.php" class="login-form">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required>
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                        <input type="submit" value="Login">
                    </form> 

                </div>
            </div>
        </main>

        <footer>
            <p>&copy; Gabriel Schiavo</p>
        </footer>

    </body>

</html>