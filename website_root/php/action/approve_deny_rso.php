<?php

    session_start();

    // Include the rso.php file
    include_once "../rso.php";

    // Check if the user is an admin
    if (!$_SESSION['user_is_super_admin'])
    {

        // Set the error message and redirect to the previous page
        $_SESSION['message_approve_deny_rso'] = "You do not have permission to approve or deny RSOs.";
        header("Location: ../../admin.html");
        exit(1);

    }

    // Check if the RSO ID is set
    if (isset($_POST['rso_id']))
    {

        // Get the RSO ID
        $rso_id = $_POST['rso_id'];

        // Get the RSO
        $rso = get_rso_by_id($rso_id);

        // Check if the RSO exists
        if ($rso)
        {

            // Check if the RSO is already approved
            if ($rso['is_approved'])
            {

                // Set the error message and redirect to the previous page
                $_SESSION['message_approve_deny_rso'] = "The RSO is already approved.";
                header("Location: ../../admin.html#section_approve_deny_rso");
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

                        // Approve the RSO
                        approve_rso_by_id($rso_id);

                        // Set the success message and redirect to the previous page
                        $_SESSION['message_approve_deny_rso'] = "The RSO has been approved.";
                        header("Location: ../../admin.html#section_approve_deny_rso");
                        break;

                    // Check if the action is deny
                    case "deny":

                        // Deny the RSO
                        deny_rso_by_id($rso_id);

                        // Set the success message and redirect to the previous page
                        $_SESSION['message_approve_deny_rso'] = "The RSO has been denied.";
                        header("Location: ../../admin.html#section_approve_deny_rso");
                        break;

                    // The action is not valid
                    default:

                        // Set the error message and redirect to the previous page
                        $_SESSION['message_approve_deny_rso'] = "The action is not valid.";
                        header("Location: ../../admin.html#section_approve_deny_rso");
                        break;

                }

            }
            else
            {

                // Set the error message and redirect to the previous page
                $_SESSION['message_approve_deny_rso'] = "The action is not set.";
                header("Location: ../../admin.html#section_approve_deny_rso");

            }

        }
        else
        {

            // Set the error message and redirect to the previous page
            $_SESSION['message_approve_deny_rso'] = "The RSO does not exist.";
            header("Location: ../../admin.html#section_approve_deny_rso");

        }

    }
    else
    {

        // Set the error message and redirect to the previous page
        $_SESSION['message_approve_deny_rso'] = "The RSO ID is not set.";
        header("Location: ../../admin.html#section_approve_deny_rso");

    }

?>