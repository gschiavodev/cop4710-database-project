<?php   

    // Start the session
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['user_id']))
    {

        // Redirect to the login page
        header("Location: ../../form/login.html");
        exit();

    }

    // Get the POST data
    $event_id = $_POST['event_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];

    // Check if the event ID is set
    if (!isset($event_id) || !isset($comment) || !isset($rating))
    {

        // Redirect to the events page
        header("Location: ../../index.html");
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

    // Check that that comment is not empty
    if (empty($comment))
    {

        // Redirect to the event page
        header("Location: ../../profile/profile_event.php?event_id=" . $event_id . "#section_comments");
        exit();

    }

    // Check that the rating is between 1 and 5
    if ($rating < 1 || $rating > 5)
    {

        // Redirect to the event page
        header("Location: ../../profile/profile_event.php?event_id=" . $event_id . "#section_comments");
        exit();

    }

    // Get the user ID
    $user_id = $_SESSION['user_id'];

    // Include the user_event_comment.php file to create the event comment
    include_once "../../user_event_comment.php";

    // Create the event comment
    $event_comment_id = create_event_comment_by_user_id_and_event_id($user_id, $event_id, $comment, $rating);

    // Redirect to the event page
    header("Location: ../../profile/profile_event.php?event_id=" . $event_id . "#section_comments");

?>