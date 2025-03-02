<?php
include 'connection.php';

// Get the current date and time
$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

// Update status to "ongoing" if the current time matches the start time
$updateOngoingQuery = "UPDATE appointment 
                       SET AppointmentStatus = 'ongoing' 
                       WHERE AppointmentStatus = 'approved' 
                       AND AppointmentDate = '$currentDate' 
                       AND TimeStart <= '$currentTime' 
                       AND TimeEnd > '$currentTime'";

mysqli_query($connection, $updateOngoingQuery);

// Update status to "completed" if the current time matches the end time
$updateCompletedQuery = "UPDATE appointment 
                         SET AppointmentStatus = 'completed' 
                         WHERE AppointmentStatus = 'ongoing' 
                         AND AppointmentDate = '$currentDate' 
                         AND TimeEnd <= '$currentTime'";

mysqli_query($connection, $updateCompletedQuery);

// Apply penalty if the patient did not show up
$updatePenaltyQuery = "UPDATE appointment 
                       SET AppointmentStatus = 'penalty' 
                       WHERE AppointmentStatus = 'ongoing' 
                       AND AppointmentDate = '$currentDate' 
                       AND TimeEnd <= '$currentTime'";

mysqli_query($connection, $updatePenaltyQuery);

echo json_encode(['status' => 'success']);
mysqli_close($connection);
?>
