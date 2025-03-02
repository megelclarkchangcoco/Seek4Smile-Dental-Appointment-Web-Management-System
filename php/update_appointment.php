<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["appointmentID"]) && isset($_POST["status"])) {
    $appointmentID = $_POST['appointmentID']; // Keep as string since it's varchar (APT00010)
    $status = $_POST['status'];

    if (empty($appointmentID) || empty($status)) {
        die("Error: Missing data! AppointmentID: " . $appointmentID . " | Status: " . $status);
    }

    $stmt = $connection->prepare("UPDATE appointment SET AppointmentStatus = ? WHERE AppointmentID = ?");
    $stmt->bind_param("ss", $status, $appointmentID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
} else {
    die("Invalid request!");
}
?>
