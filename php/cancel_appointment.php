<?php
include 'connection.php'; // Include the database connection

// Get the appointment ID and cancellation reason from the POST request
$appointmentID = $_POST['appointmentID'] ?? null;
$cancelationReason = $_POST['cancelationReason'] ?? null;

// Validate input
if (empty($appointmentID) || empty($cancelationReason)) {
    die('Invalid input.');
}

// Update the appointment status and cancellation reason in the database
$query = "UPDATE appointment 
          SET AppointmentStatus = 'Canceled', CancelationReason = ? 
          WHERE AppointmentID = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    // Use 'ss' if AppointmentID is a string (e.g., "APTID001"), otherwise 'si' if it's an integer
    mysqli_stmt_bind_param($stmt, 'ss', $cancelationReason, $appointmentID);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo 'Success';
    } else {
        echo 'Failed';
    }

    mysqli_stmt_close($stmt);
} else {
    echo 'Failed to prepare statement.';
}

mysqli_close($connection);
?>
