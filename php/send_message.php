<?php
session_start();
include "connection.php";

if (isset($_POST['outgoing_id'], $_POST['incoming_id'], $_POST['message'])) {
    $outgoing_id = $_POST['outgoing_id'];
    $incoming_id = $_POST['incoming_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $incoming_id, $outgoing_id, $message);
        $stmt->execute();
    }
}
?>
