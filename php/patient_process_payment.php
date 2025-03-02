<?php
header('Content-Type: application/json');
include 'connection.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$patientID = $_SESSION['PatientID'] ?? null;
$billingID = $data['billingID'] ?? null;
$cardNumber = $data['cardNumber'] ?? null;
$amount = $data['amount'] ?? null;
$pin = $data['pin'] ?? null;

if (!$patientID || !$billingID || !$cardNumber || !$amount || !$pin) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

// Validate Card Exists & PIN is Correct
$queryCard = "SELECT CardID FROM card WHERE PatientID = ? AND CardNumber = ? AND CVV = ?";
$stmtCard = mysqli_prepare($connection, $queryCard);
mysqli_stmt_bind_param($stmtCard, "sss", $patientID, $cardNumber, $pin);
mysqli_stmt_execute($stmtCard);
mysqli_stmt_store_result($stmtCard);
if (mysqli_stmt_num_rows($stmtCard) === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid Card Number or PIN"]);
    exit;
}
mysqli_stmt_close($stmtCard);

// Get Total Fee & Total Paid So Far
$queryBilling = "SELECT TotalFee, 
                (SELECT SUM(PaymentAmount) FROM payments WHERE BillingID = ?) AS TotalPaid
                FROM appointmentbilling WHERE BillingID = ? AND PatientID = ?";
$stmtBilling = mysqli_prepare($connection, $queryBilling);
mysqli_stmt_bind_param($stmtBilling, "sss", $billingID, $billingID, $patientID);
mysqli_stmt_execute($stmtBilling);
mysqli_stmt_bind_result($stmtBilling, $totalFee, $totalPaid);
mysqli_stmt_fetch($stmtBilling);
mysqli_stmt_close($stmtBilling);

$totalPaid = $totalPaid ?? 0;
$remainingBalance = $totalFee - $totalPaid;

if ($amount > $remainingBalance) {
    echo json_encode(["status" => "error", "message" => "Payment exceeds remaining balance."]);
    exit;
}

// Insert Payment Record
$queryPayment = "INSERT INTO payments (BillingID, PaymentAmount, PaymentDate, PaymentMethod) 
                 VALUES (?, ?, CURDATE(), 'Card')";
$stmtPayment = mysqli_prepare($connection, $queryPayment);
mysqli_stmt_bind_param($stmtPayment, "sd", $billingID, $amount);
if (!mysqli_stmt_execute($stmtPayment)) {
    echo json_encode(["status" => "error", "message" => "Payment failed: " . mysqli_error($connection)]);
    exit;
}
mysqli_stmt_close($stmtPayment);

// Update Payment Status if Fully Paid
$newTotalPaid = $totalPaid + $amount;
if ($newTotalPaid >= $totalFee) {
    $queryUpdateStatus = "UPDATE appointmentbilling SET PaymentStatus = 'paid' WHERE BillingID = ?";
    $stmtUpdateStatus = mysqli_prepare($connection, $queryUpdateStatus);
    mysqli_stmt_bind_param($stmtUpdateStatus, "s", $billingID);
    mysqli_stmt_execute($stmtUpdateStatus);
    mysqli_stmt_close($stmtUpdateStatus);
}

mysqli_close($connection);
echo json_encode(["status" => "success", "message" => "Payment successful"]);
?>
