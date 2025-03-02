<?php
session_start();
include 'connection.php';

// Check if the required POST data is set
if (isset($_POST['outgoing_id'], $_POST['incoming_id'], $_POST['message'])) {
    $outgoing_id = mysqli_real_escape_string($connection, $_POST['outgoing_id']);
    $incoming_id = mysqli_real_escape_string($connection, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($connection, $_POST['message']);

    if (!empty($message)) {
        // Insert the message into the database using a prepared statement
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sss", $incoming_id, $outgoing_id, $message);
            if ($stmt->execute()) {
                echo "Message sent successfully!";
            } else {
                echo "Failed to send message: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement: " . $connection->error;
        }
    } else {
        echo "Message cannot be empty.";
    }
} else {
    echo "Invalid request.";
}

$connection->close();
?>