<?php
session_start();
include 'php/connection.php';

if (isset($_SESSION['UserType'])) {
    $userType = $_SESSION['UserType'];
    $idField = '';
    $table = '';

    switch ($userType) {
        case 'Admin':
            $idField = 'AdminID';
            $table = 'admin';
            break;
        case 'BillingSpecialist':
            $idField = 'SpecialistID';
            $table = 'billingspecialist';
            break;
        case 'Dentist':
            $idField = 'DentistID';
            $table = 'dentist';
            break;
        case 'Assistant':
            $idField = 'AssistantID';
            $table = 'dentistassistant';
            break;
        case 'Patient':
            $idField = 'PatientID';
            $table = 'patient';
            break;
    }

    if ($idField && isset($_SESSION[$idField])) {
        $userId = $_SESSION[$idField];
        
        // Log logout activity
        $logQuery = "INSERT INTO activity_log (UserType, UserID, Activity) 
                   VALUES ('$userType', '$userId', 'Logout')";
        mysqli_query($connection, $logQuery);

        // Update status to Offline
        $updateQuery = "UPDATE `$table` SET `status` = 'Offline' WHERE `$idField` = '$userId'";
        mysqli_query($connection, $updateQuery);
    }
}

// Destroy session
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>