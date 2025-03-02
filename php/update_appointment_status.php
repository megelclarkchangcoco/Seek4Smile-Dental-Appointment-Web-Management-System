<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["appointmentID"]) && isset($_POST["status"])) {
    $appointmentID = $_POST['appointmentID'];  // Appointment ID
    $status = $_POST['status'];  // Status to update

    // Prepare the statement to prevent SQL Injection
    $stmt = $connection->prepare("UPDATE appointment SET AppointmentStatus = ? WHERE AppointmentID = ?");
    $stmt->bind_param("ss", $status, $appointmentID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $connection->close();
}
?>
