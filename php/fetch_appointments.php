<?php
include 'connection.php';

// Fetch only "Approved" appointments
$query = "SELECT a.AppointmentID, a.PatientID, p.Firstname, a.AppointmentDate, 
                 a.TimeStart, a.TimeEnd, a.AppointmentType, a.AppointmentStatus
          FROM appointment a
          LEFT JOIN patient p ON a.PatientID = p.PatientID
          WHERE a.AppointmentStatus = 'Approved'";

$result = mysqli_query($connection, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => $row['Firstname'],
        'start' => $row['AppointmentDate'],
        'extendedProps' => [
            'patient' => $row['Firstname'],
            'time' => date("h:i A", strtotime($row['TimeStart'])) . " - " . date("h:i A", strtotime($row['TimeEnd'])),
            'reason' => $row['AppointmentType'],
            'status' => $row['AppointmentStatus']
        ]
    ];
}

echo json_encode($events);
?>
