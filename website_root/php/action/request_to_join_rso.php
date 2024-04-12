<?php

    // Start the session
    session_start();

    // Check if the post data is set
    if (!isset($_POST["rso_id"]) || !isset($_POST["user_id"]))
    {

        // Redirect to the homepage
        header("Location: ../profile/profile_rso.php?id=" . $rso_id);
        exit();

    }

    // Include necessary files
    include_once "../user_in_rso.php";

    // Get the post data
    $rso_id = $_POST["rso_id"];
    $user_id = $_POST["user_id"];

    // Add the user to the RSO (default to not approved)
    $rso_user_id = add_user_by_id_to_rso_by_id($user_id, $rso_id);

    // Check if the user was added to the RSO
    if ($rso_user_id)
    {

        // Redirect to the RSO page
        $_SESSION["message_request_to_join_rso"] = "Request to join RSO sent successfully.";
        header("Location: ../profile/profile_rso.php?id=" . $rso_id);
        exit();

    }
    else
    {

        // Redirect to the homepage
        $_SESSION["message_request_to_join_rso"] = "Failed to send request to join RSO.";
        header("Location: ../profile/profile_rso.php?id=" . $rso_id);
        exit();

    }

?>