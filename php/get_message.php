<?php
session_start();
include "connection.php";

if (isset($_POST['outgoing_id'], $_POST['incoming_id'])) {
    $outgoing_id = $_POST['outgoing_id'];
    $incoming_id = $_POST['incoming_id'];

    // Fetch profile images for the patient and dentist
    $patient_img_sql = "SELECT img FROM patient WHERE PatientID = ?";
    $dentist_img_sql = "SELECT img FROM dentist WHERE DentistID = ?";
    
    // Prepare and execute the query to get the patient's profile image
    $patient_stmt = $connection->prepare($patient_img_sql);
    $patient_stmt->bind_param("s", $incoming_id);
    $patient_stmt->execute();
    $patient_result = $patient_stmt->get_result();
    $patient_img = $patient_result->fetch_assoc()['img'] ?? 'img/user_default.png'; // Default image if not found

    // Prepare and execute the query to get the dentist's profile image
    $dentist_stmt = $connection->prepare($dentist_img_sql);
    $dentist_stmt->bind_param("s", $outgoing_id);
    $dentist_stmt->execute();
    $dentist_result = $dentist_stmt->get_result();
    $dentist_img = $dentist_result->fetch_assoc()['img'] ?? 'img/user_default.png'; // Default image if not found

    // Debugging: Check the fetched image paths
    error_log("Patient Image: " . $patient_img);
    error_log("Dentist Image: " . $dentist_img);

    // Fetch chat messages between the patient and dentist
    $sql = "SELECT * FROM messages 
            WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) 
            OR (incoming_msg_id = ? AND outgoing_msg_id = ?) 
            ORDER BY created_at ASC";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssss", $outgoing_id, $incoming_id, $incoming_id, $outgoing_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Loop through the messages and display them
    while ($row = $result->fetch_assoc()) {
        if ($row['outgoing_msg_id'] == $outgoing_id) {
            // Outgoing message (sent by the current user)
            echo '<div class="chat outgoing">
                    <div class="details">';
            if (strpos($row['msg'], 'chat_images/') === 0) {
                // If the message is an image, display the image
                echo '<img src="' . htmlspecialchars($row['msg']) . '" class="chat-image">';
            } else {
                // Otherwise, display the text message
                echo '<p>' . htmlspecialchars($row['msg']) . '<br>
                      <span class="time">' . date("g:ia", strtotime($row['created_at'])) . '</span></p>';
            }
            echo '</div></div>';
        } else {
            // Incoming message (received by the current user)
            echo '<div class="chat incoming">
                    <img src="' . htmlspecialchars($patient_img) . '" alt="">
                    <div class="details">';
            if (strpos($row['msg'], 'chat_images/') === 0) {
                // If the message is an image, display the image
                echo '<img src="' . htmlspecialchars($row['msg']) . '" class="chat-image">';
            } else {
                // Otherwise, display the text message
                echo '<p>' . htmlspecialchars($row['msg']) . '<br>
                      <span class="time">' . date("g:ia", strtotime($row['created_at'])) . '</span></p>';
            }
            echo '</div></div>';
        }
    }
}
?>