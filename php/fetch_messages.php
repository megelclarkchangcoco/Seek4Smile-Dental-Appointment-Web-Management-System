<?php
include 'connection.php';
session_start();

$dentist_id = $_SESSION['DentistID'];
$patient_id = mysqli_real_escape_string($connection, $_GET['patient_id']);

// Fetch chat history
$chat_query = "SELECT * FROM messages 
              LEFT JOIN patient ON patient.PatientID = messages.outgoing_msg_id
              LEFT JOIN dentist ON dentist.DentistID = messages.outgoing_msg_id
              WHERE (outgoing_msg_id = '$dentist_id' AND incoming_msg_id = '$patient_id')
              OR (outgoing_msg_id = '$patient_id' AND incoming_msg_id = '$dentist_id')
              ORDER BY msg_id ASC";
$chat_result = mysqli_query($connection, $chat_query);

$messages = [];
while ($message = mysqli_fetch_assoc($chat_result)) {
    $messages[] = $message;
}

echo json_encode($messages);
?>