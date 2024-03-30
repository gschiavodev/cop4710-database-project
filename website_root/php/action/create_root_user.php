<?php

    // Include the database connection
    include_once "/var/www/html/php/database.php";

    // User data
    $first_name = 'root';
    $last_name = '';
    $email = 'root@server.com';
    $password = 'password';

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Connect to the database
    $conn = connect_to_database();

    // Create a root user
    $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, university_email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $first_name, $last_name, $email, $hashed_password);
    $stmt->execute();

    // Close connection to the database
    close_connection_to_database($conn);
    
?>
