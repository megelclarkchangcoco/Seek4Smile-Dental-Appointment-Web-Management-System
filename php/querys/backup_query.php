<?php
include 'connection.php';
session_start();

error_log("SESSION DATA: " . print_r($_SESSION, true));

if (!isset($_SESSION['PatientID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in. Please log in first.']);
    exit;
}

$patientID = $_SESSION['PatientID'];
$dentistID = $_POST['dentistID'] ?? null;
$appointmentType = $_POST['appointmentType'] ?? null;
$appointmentLaboratory = $_POST['appointmentLaboratory'] ?? null;
$appointmentProcedure = $_POST['appointmentProcedure'] ?? null;
$appointmentTreatment = $_POST['appointmentTreatment'] ?? null;
$appointmentDate = $_POST['appointmentDate'] ?? null;
$timeStart = $_POST['timeStart'] ?? null;
$timeEnd = $_POST['timeEnd'] ?? null;
$paymentType = $_POST['paymentType'] ?? null;
$reason = $_POST['reason'] ?? null;

error_log("POST DATA: " . print_r($_POST, true));

$requiredFields = ['dentistID', 'appointmentType', 'appointmentDate', 'timeStart', 'timeEnd', 'paymentType', 'reason'];

if ($appointmentType === 'Laboratory') {
    $requiredFields[] = 'appointmentLaboratory';
} elseif ($appointmentType === 'procedure') {
    $requiredFields[] = 'appointmentProcedure';
} elseif ($appointmentType === 'treatment') {
    $requiredFields[] = 'appointmentTreatment';
}

$missingFields = [];
foreach ($requiredFields as $field) {
    if (empty($$field)) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing fields: ' . implode(', ', $missingFields)]);
    exit;
}

mysqli_begin_transaction($connection, MYSQLI_TRANS_START_READ_WRITE);

try {
    $query = "INSERT INTO appointment (PatientID, DentistID, AppointmentType, AppointmentLaboratory, AppointmentProcedure, AppointmentTreatment, AppointmentDate, TimeStart, TimeEnd, PaymentType, Reason, AppointmentStatus) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled')";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $patientID, $dentistID, $appointmentType, $appointmentLaboratory, $appointmentProcedure, $appointmentTreatment, $appointmentDate, $timeStart, $timeEnd, $paymentType, $reason);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Appointment error: " . mysqli_error($connection));
    }
    $appointmentID = mysqli_insert_id($connection);
    error_log("Generated AppointmentID: " . $appointmentID);

    $appointmentFee = 0;
    $laboratoryFee = 0;
    $procedureFee = 0;
    $treatmentFee = 0;

    switch ($appointmentType) {
        case 'consultation':
            $appointmentFee = 1000;
            break;
        case 'checkup':
            $appointmentFee = 500;
            break;
        case 'treatment':
        case 'Laboratory':
        case 'procedure':
            $appointmentFee = 0;
            break;
        case 'followup':
            $appointmentFee = 1000;
            break;
    }

    if ($appointmentLaboratory === 'treatment') {
        $laboratoryFee = 5000;
    }

    switch ($appointmentTreatment) {
        case 'preventive_care':
            $treatmentFee = 1500;
            break;
        case 'restorative_care':
            $treatmentFee = 5000;
            break;
        case 'cosmetic_care':
            $treatmentFee = 10000;
            break;
        case 'orthodontic_care':
        case 'surgical_care':
            $treatmentFee = 20000;
            break;
    }

    switch ($appointmentProcedure) {
        case 'dental_cleaning':
            $procedureFee = 1000;
            break;
        case 'dental_filling':
        case 'braces_adjustment':
            $procedureFee = 1500;
            break;
        case 'teeth_whitening':
        case 'fluoride_treatment':
            $procedureFee = 5000;
            break;
        case 'denture_fitting':
            $procedureFee = 10000;
            break;
        case 'examination':
            $procedureFee = 500;
            break;
        case 'plaque_tartar_removal':
            $procedureFee = 1000;
            break;
    }

    $billingQuery = "INSERT INTO appointmentbilling (AppointmentID, PatientID, AppointmentFee, LaboratoryFee, ProcedureFee, TreatmentFee, PaymentType) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
    $billingStmt = mysqli_prepare($connection, $billingQuery);
    mysqli_stmt_bind_param($billingStmt, "ssdddds", $appointmentID, $patientID, $appointmentFee, $laboratoryFee, $procedureFee, $treatmentFee, $paymentType);
    
    if (!mysqli_stmt_execute($billingStmt)) {
        throw new Exception("Billing error: " . mysqli_error($connection));
    }
    $billingID = mysqli_insert_id($connection);
    error_log("Generated BillingID: " . $billingID);

    $invoiceQuery = "INSERT INTO invoice (BillingID, PatientID, TotalFee, PaymentStatus) 
                     VALUES (?, ?, ?, 'unpaid')";
    $invoiceStmt = mysqli_prepare($connection, $invoiceQuery);
    $totalFee = $appointmentFee + $laboratoryFee + $procedureFee + $treatmentFee;
    mysqli_stmt_bind_param($invoiceStmt, "ssd", $billingID, $patientID, $totalFee);
    
    if (!mysqli_stmt_execute($invoiceStmt)) {
        throw new Exception("Invoice error: " . mysqli_error($connection));
    }

    mysqli_commit($connection);
    echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully']);

} catch (Exception $e) {
    mysqli_rollback($connection);
    error_log("Transaction Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
} finally {
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($billingStmt);
    mysqli_stmt_close($invoiceStmt);
    mysqli_close($connection);
}
?> 

