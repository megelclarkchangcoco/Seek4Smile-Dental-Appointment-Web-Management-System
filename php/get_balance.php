<?php
header('Content-Type: application/json');
include 'connection.php';
session_start();

if (!isset($_SESSION['PatientID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$patientID = $_SESSION['PatientID'];

$queryBalance = "SELECT SUM(ab.TotalFee) - COALESCE((SELECT SUM(p.PaymentAmount) 
                    FROM payments p 
                    WHERE p.BillingID = ab.BillingID), 0) AS totalUnpaid
                FROM appointmentbilling ab
                JOIN appointment a ON ab.AppointmentID = a.AppointmentID
                WHERE ab.PatientID = ? 
                AND ab.PaymentStatus = 'unpaid'
                AND a.AppointmentStatus IN ('Completed', 'Approved', 'Scheduled')";

$stmtBalance = mysqli_prepare($connection, $queryBalance);
mysqli_stmt_bind_param($stmtBalance, "s", $patientID);
mysqli_stmt_execute($stmtBalance);
mysqli_stmt_bind_result($stmtBalance, $totalUnpaid);
mysqli_stmt_fetch($stmtBalance);
mysqli_stmt_close($stmtBalance);

// Get unpaid penalties
$queryPenalty = "SELECT COUNT(*) AS penaltyCount FROM appointment WHERE PatientID = ? AND AppointmentStatus = 'penalty'";
$stmtPenalty = mysqli_prepare($connection, $queryPenalty);
mysqli_stmt_bind_param($stmtPenalty, "s", $patientID);
mysqli_stmt_execute($stmtPenalty);
mysqli_stmt_bind_result($stmtPenalty, $penaltyCount);
mysqli_stmt_fetch($stmtPenalty);
mysqli_stmt_close($stmtPenalty);

$penaltyFee = $penaltyCount * 2000;
$totalBalance = max(($totalUnpaid ?? 0) + $penaltyFee, 0);

echo json_encode(['status' => 'success', 'totalBalance' => $totalBalance]);
?>
