<?php

    session_start();

    // Include necessary files
    include_once "../event.php";
    include_once "../public_event.php";

    // Check if the user is an admin
    if (!$_SESSION['user_is_super_admin'])
    {

        // Set the error message and redirect to the previous page
        $_SESSION['message_approve_deny_public_event'] = "You do not have permission to approve or deny public events.";
        header("Location: ../../admin.html");
        exit(1);

    }

    // Check if the RSO ID is set
    if (isset($_POST['event_id']))
    {

        // Get the event ID
        $event_id = $_POST['event_id'];

        // Get the public event
        $public_event = get_public_event_by_event_id($event_id);

        // Check if the public event exists
        if ($public_event)
        {

            // Check if the public event is already approved
            if ($public_event['is_approved'])
            {

                // Set the error message and redirect to the previous page
                $_SESSION['message_approve_deny_public_event'] = "The public event is already approved.";
                header("Location: ../../admin.html#section_approve_deny_public_event");
                exit(1);

            }

            // Check if the approve or deny action is set
            if (isset($_POST['action']))
            {

                // Get the action
                $action = $_POST['action'];

                // Check the action
                switch ($action)
                {

                    // Check if the action is approve
                    case "approve":

                        // Approve the public event
                        approve_public_event_by_event_id($event_id);

                        // Set the success message and redirect to the previous page
                        $_SESSION['message_approve_deny_public_event'] = "The public event has been approved.";
                        header("Location: ../../admin.html#section_approve_deny_public_event");
                        exit(0);

                    // Check if the action is deny
                    case "deny":

                        // Deny the public event
                        deny_public_event_by_event_id($event_id);

                        // Set the success message and redirect to the previous page
                        $_SESSION['message_approve_deny_public_event'] = "The public event has been denied.";
                        header("Location: ../../admin.html#section_approve_deny_public_event");
                        exit(0);

                    // Default case
                    default:

                        // Set the error message and redirect to the previous page
                        $_SESSION['message_approve_deny_public_event'] = "Invalid action.";
                        header("Location: ../../admin.html#section_approve_deny_public_event");
                        exit(1);

                }

            }
            else
            {

                // Set the error message and redirect to the previous page
                $_SESSION['message_approve_deny_public_event'] = "Action not set.";
                header("Location: ../../admin.html#section_approve_deny_public_event");
                exit(1);

            }

        }
        else
        {

            // Set the error message and redirect to the previous page
            $_SESSION['message_approve_deny_public_event'] = "Public event not found.";
            header("Location: ../../admin.html#section_approve_deny_public_event");
            exit(1);

        }

    }
    else
    {

        // Set the error message and redirect to the previous page
        $_SESSION['message_approve_deny_public_event'] = "Form data not set.";
        header("Location: ../../admin.html#section_approve_deny_public_event");
        exit(1);

    }

?>

