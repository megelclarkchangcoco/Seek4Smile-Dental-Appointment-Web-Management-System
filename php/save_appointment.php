<?php
header('Content-Type: application/json'); // Ensure JSON output

include 'connection.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$requiredFields = ['dentistID', 'appointmentType', 'appointmentDate', 'timeStart', 'timeEnd', 'paymentType', 'reason'];

if ($appointmentType === 'Laboratory') {
    $requiredFields[] = 'appointmentLaboratory';
} elseif ($appointmentType === 'Procedure') {
    $requiredFields[] = 'appointmentProcedure';
} elseif ($appointmentType === 'Treatment') {
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

mysqli_begin_transaction($connection); // ✅ START TRANSACTION

try {
    // 🔹 **1️⃣ CHECK FOR OVERLAPPING APPOINTMENTS**
    $overlapQuery = "SELECT COUNT(*) FROM appointment 
                     WHERE DentistID = ? 
                     AND AppointmentDate = ? 
                     AND AppointmentStatus = 'approved' 
                     AND ((TimeStart < ? AND TimeEnd > ?)  
                      OR (TimeStart < ? AND TimeEnd > ?)  
                      OR (TimeStart >= ? AND TimeEnd <= ?))"; 
    
    $stmtOverlap = mysqli_prepare($connection, $overlapQuery);
    mysqli_stmt_bind_param($stmtOverlap, "ssssssss", $dentistID, $appointmentDate, $timeEnd, $timeStart, $timeStart, $timeEnd, $timeStart, $timeEnd);
    mysqli_stmt_execute($stmtOverlap);
    mysqli_stmt_bind_result($stmtOverlap, $overlappingCount);
    mysqli_stmt_fetch($stmtOverlap);
    mysqli_stmt_close($stmtOverlap);

    if ($overlappingCount > 0) {
        throw new Exception("This time slot is already booked for this dentist.");
    }

    // 🔹 **2️⃣ INSERT INTO `appointment` TABLE**
    $query = "INSERT INTO appointment 
              (PatientID, DentistID, AppointmentType, AppointmentLaboratory, AppointmentProcedure, AppointmentTreatment, AppointmentDate, TimeStart, TimeEnd, PaymentType, Reason, AppointmentStatus) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'scheduled')";
    
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssssssssss", $patientID, $dentistID, $appointmentType, $appointmentLaboratory, $appointmentProcedure, $appointmentTreatment, $appointmentDate, $timeStart, $timeEnd, $paymentType, $reason);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error saving appointment: ' . mysqli_error($connection));
    }

    // ✅ **Retrieve the LAST INSERTED AppointmentID (Trigger Generated)**
    $query_get_id = "SELECT AppointmentID FROM appointment WHERE PatientID = ? AND DentistID = ? ORDER BY CreatedAt DESC LIMIT 1";
    $stmt_get_id = mysqli_prepare($connection, $query_get_id);
    mysqli_stmt_bind_param($stmt_get_id, "ss", $patientID, $dentistID);
    mysqli_stmt_execute($stmt_get_id);
    mysqli_stmt_bind_result($stmt_get_id, $appointmentID);
    mysqli_stmt_fetch($stmt_get_id);
    mysqli_stmt_close($stmt_get_id);

    if (!$appointmentID) {
        throw new Exception('Error retrieving AppointmentID after insert.');
    }

    // 🔹 **3️⃣ FETCH PRICING DETAILS**
    function getPrice($connection, $type, $subcategory) {
        $query = "SELECT Price FROM appointment_pricing WHERE AppointmentType = ? AND (SubCategory = ? OR SubCategory IS NULL)";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "ss", $type, $subcategory);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $price);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $price ?? 0;
    }

    $appointmentFee = getPrice($connection, $appointmentType, $appointmentType);
    $laboratoryFee = $appointmentLaboratory ? getPrice($connection, 'Laboratory', $appointmentLaboratory) : 0;
    $procedureFee = $appointmentProcedure ? getPrice($connection, 'Procedure', $appointmentProcedure) : 0;
    $treatmentFee = $appointmentTreatment ? getPrice($connection, 'Treatment', $appointmentTreatment) : 0;

    // ✅ **Calculate Total Fee**
    $totalFee = $appointmentFee + $laboratoryFee + $procedureFee + $treatmentFee;

    // 🔹 **4️⃣ INSERT INTO `appointmentbilling` TABLE**
    $billingQuery = "INSERT INTO appointmentbilling 
                    (AppointmentID, PatientID, AppointmentFee, LaboratoryFee, ProcedureFee, TreatmentFee, PaymentType, PaymentStatus) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'unpaid')";

    $stmtBilling = mysqli_prepare($connection, $billingQuery);
    mysqli_stmt_bind_param($stmtBilling, "sssssss", $appointmentID, $patientID, $appointmentFee, $laboratoryFee, $procedureFee, $treatmentFee, $paymentType);

    if (!mysqli_stmt_execute($stmtBilling)) {
        throw new Exception('Error saving billing details: ' . mysqli_error($connection));
    }

    // 🔹 **5️⃣ COMMIT TRANSACTION**
    mysqli_commit($connection);
    echo json_encode(['status' => 'success', 'message' => 'Appointment and billing saved successfully']);

} catch (Exception $e) {
    mysqli_rollback($connection); // ❌ ROLLBACK ON ERROR
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

// ✅ Close Statements & Connection
if (isset($stmt)) {
    mysqli_stmt_close($stmt);
}
if (isset($stmtBilling)) {
    mysqli_stmt_close($stmtBilling);
}
mysqli_close($connection);
?>
