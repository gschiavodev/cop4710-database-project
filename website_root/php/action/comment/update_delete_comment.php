<?php

    // Start the session
    session_start();

    // Get the POST data
    $event_id = $_POST['event_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Check if the event ID is set
    if (!isset($event_id) || !isset($comment) || !isset($rating))
    {

        // Redirect to the events page
        header("Location: ../../../index.html");
        exit();

    }

    // Include the event.php file to get the event
    include_once "../../event.php";

    // Get the event by ID
    $event = get_event_by_id($event_id);

    // Check if the event was found
    if (!$event)
    {

        // Redirect to the events page
        header("Location: ../../../index.html");
        exit();

    }

    // Check form action
    switch ($_POST['action'])
    {

        case 'update':
        {

            // Include the user_event_comment.php file to update the event comment
            include_once "../../user_event_comment.php";

            // Get the user ID
            $user_id = $_SESSION['user_id'];

            // Update the event comment
            update_event_comment_by_user_id_and_event_id($user_id, $event_id, $comment, $rating);

            break;
        
        }

        case 'delete':
        {

            // Get the user ID
            $user_id = $_SESSION['user_id'];

            // Include the user_event_comment.php file to delete the event comment
            include_once "../../user_event_comment.php";

            // Delete the event comment
            delete_event_comment_by_user_id_and_event_id($user_id, $event_id);

            break;
        
        }

    }

    // Redirect to the event page
    header("Location: ../../profile/profile_event.php?event_id=" . $event_id . "#section_comments");

?>