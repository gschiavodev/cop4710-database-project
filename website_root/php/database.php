<?php

    function connect_to_database()
    {

        // Database connection information
        $servername = "mysql";
        $username = "webserver";
        $password = "X7g9#4vZ1$2cQ5";
        $database = "college_events";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);

        // Return connection
        return $conn;
        
    }

    function close_connection_to_database($conn) 
    {

        // Close the connection
        mysqli_close($conn);
        
    }

?>
