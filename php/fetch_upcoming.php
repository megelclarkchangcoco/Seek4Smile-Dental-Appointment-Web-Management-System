<?php
include 'connection.php';

// Get today's date
$today = date("Y-m-d");

// Fetch upcoming appointments (only approved ones from today onwards)
$query = "SELECT a.AppointmentID, a.PatientID, p.Firstname, a.AppointmentDate, 
                 a.TimeStart, a.TimeEnd, a.AppointmentType, a.AppointmentStatus
          FROM appointment a
          LEFT JOIN patient p ON a.PatientID = p.PatientID
          WHERE a.AppointmentStatus = 'Approved' AND a.AppointmentDate >= '$today'
          ORDER BY a.AppointmentDate ASC";

$result = mysqli_query($connection, $query);

$upcomingAppointments = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Format date and time
    $formattedDate = date("M d", strtotime($row['AppointmentDate'])); // Example: FEB 21
    $dayOfWeek = date("l", strtotime($row['AppointmentDate'])); // Example: Monday
    $timeRange = date("h:i A", strtotime($row['TimeStart'])) . " - " . date("h:i A", strtotime($row['TimeEnd']));

    $upcomingAppointments[] = [
        'date' => $formattedDate,
        'day' => $dayOfWeek,
        'time' => $timeRange,
        'patient' => $row['Firstname'],
        'reason' => $row['AppointmentType']
    ];
}

// Return JSON response
echo json_encode($upcomingAppointments);
?>
