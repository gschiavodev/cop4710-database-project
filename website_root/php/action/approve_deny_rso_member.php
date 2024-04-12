<?php

    // Start the session
    session_start();

    // Include necessary files
    include_once "../rso.php";
    include_once "../user.php";
    include_once "../user_in_rso.php";

    // Check if the post data is set
    if (!isset($_POST["action"]) || !isset($_POST["user_id"]) || !isset($_POST["rso_id"]))
    {

        // Redirect to the homepage
        header("Location: ../../index.php");
        exit();

    }

    // Get the post data
    $action = $_POST["action"];
    $user_id = $_POST["user_id"];
    $rso_id = $_POST["rso_id"];

    // Get the admin id of the RSO
    $admin_id = get_rso_admin_id_by_rso_id($rso_id);

    // Check if the user is an admin of the RSO
    if ($admin_id != $_SESSION["user_id"])
    {

        // Redirect to the homepage
        header("Location: ../../index.php");
        exit();

    }

    // Include necessary files
    include_once "../user_in_rso.php";

    // Switch on the action
    switch ($action)
    {

        // If the action is to approve a user
        case "approve":
        {

            // Approve the user
            $_SESSION["message_approve_deny_rso_member"] = "User approved successfully.";
            approve_user_in_rso_by_user_and_rso_id($user_id, $rso_id);
            break;

        }

        // If the action is to deny a user
        case "deny":
        {

            // Deny the user
            $_SESSION["message_approve_deny_rso_member"] = "User denied successfully.";
            deny_user_in_rso_by_user_and_rso_id($user_id, $rso_id);
            break;

        }

    }

    // Redirect to the RSO page
    header("Location: ../profile/profile_rso.php?rso_id=" . $rso_id . "#section_approve_deny_rso_member");
    exit();

?>