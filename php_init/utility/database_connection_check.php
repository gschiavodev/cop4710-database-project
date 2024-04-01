<?php

    // Include the database connection file
    include_once "/var/www/html/php/database.php";

    // Connect to the database
    $conn = connect_to_database();

    // Check if the connection was successful
    if (!$conn) {

        // Connection failed
        echo "DB_CONNECTION_CHECK: Connection failed: " . mysqli_connect_error() . "\n\n";
        exit(1);  // Exit with a non-zero status code

    }
    else {

        // Connection successful
        echo "DB_CONNECTION_CHECK: Connection successful!\n\n";

    }

    // Close the connection
    close_connection_to_database($conn);

?>