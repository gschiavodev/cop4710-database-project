<?php

    // Include database connection info
    include_once "database.php";

    function get_event_comments_by_event_id($event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare the SQL statement
        $select_event_comments = $conn->prepare("SELECT * FROM user_event_comment WHERE event_id = ?");
        $select_event_comments->bind_param("i", $event_id);
        $select_event_comments->execute();

        // Get the result of the query
        $event_comments_result = $select_event_comments->get_result();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the event comments
        return $event_comments_result;

    }

    function get_event_comment_by_event_id_and_user_id($event_id, $user_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare the SQL statement
        $select_event_comment = $conn->prepare("SELECT * FROM user_event_comment WHERE event_id = ? AND user_id = ?");
        $select_event_comment->bind_param("ii", $event_id, $user_id);
        $select_event_comment->execute();

        // Get the result of the query
        $event_comment_result = $select_event_comment->get_result();

        // Get the event comment
        $event_comment = $event_comment_result->fetch_assoc();

        // Close the database connection
        close_connection_to_database($conn);

        // Return the event comment
        return $event_comment;

    }

    function create_event_comment_by_user_id_and_event_id($user_id, $event_id, $comment, $rating)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare the SQL statement
        $insert_event_comment = $conn->prepare("INSERT INTO user_event_comment (user_id, event_id, comment, rating) VALUES (?, ?, ?, ?)");
        $insert_event_comment->bind_param("iisi", $user_id, $event_id, trim($comment), $rating);
        $insert_event_comment->execute();

        // Get the event comment ID of the created event comment
        $event_comment_id = $insert_event_comment->insert_id;

        // Close the database connection
        close_connection_to_database($conn);

        // Return the event comment ID
        return $event_comment_id;

    }

    function update_event_comment_by_user_id_and_event_id($user_id, $event_id, $comment, $rating)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare the SQL statement
        $update_event_comment = $conn->prepare("UPDATE user_event_comment SET comment = ?, rating = ? WHERE user_id = ? AND event_id = ?");
        $update_event_comment->bind_param("siii", trim($comment), $rating, $user_id, $event_id);
        $update_event_comment->execute();

        // Check if the event comment was updated
        $event_comment_updated = $update_event_comment->affected_rows > 0;

        // Close the database connection
        close_connection_to_database($conn);

        // Return if the event comment was updated
        return $event_comment_updated;

    }

    function delete_event_comment_by_user_id_and_event_id($user_id, $event_id)
    {

        // Connect to the database
        $conn = connect_to_database();

        // Prepare the SQL statement
        $delete_event_comment = $conn->prepare("DELETE FROM user_event_comment WHERE user_id = ? AND event_id = ?");
        $delete_event_comment->bind_param("ii", $user_id, $event_id);
        $delete_event_comment->execute();

        // Check if the event comment was deleted
        $event_comment_deleted = $delete_event_comment->affected_rows > 0;

        // Close the database connection
        close_connection_to_database($conn);

        // Return if the event comment was deleted
        return $event_comment_deleted;

    }

?>