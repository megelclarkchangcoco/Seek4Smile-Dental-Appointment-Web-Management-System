<?php
header('Content-Type: application/json'); // Ensure the response is always JSON
include 'connection.php';
session_start();

// ✅ Enable error reporting (logs PHP errors)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Start with a response array
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

try {
    if (!isset($_SESSION['PatientID'])) {
        throw new Exception("User not logged in.");
    }

    $patientID = $_SESSION['PatientID'];
    $billingID = $_POST['billingID'] ?? null;
    $amount = $_POST['amount'] ?? null;
    $cardID = $_POST['cardID'] ?? null;
    $cvv = $_POST['cvv'] ?? null;
    $paymentMethod = "Card"; 
    $paymentDate = date('Y-m-d');

    if (!$billingID || !$amount || !$cardID || !$cvv) {
        throw new Exception("Missing required fields.");
    }

    // ✅ Validate the card
    $cardQuery = "SELECT * FROM card WHERE PatientID = ? AND CardID = ? AND CVV = ?";
    $stmt = mysqli_prepare($connection, $cardQuery);
    mysqli_stmt_bind_param($stmt, "sss", $patientID, $cardID, $cvv);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 0) {
        throw new Exception("Invalid card details.");
    }

    mysqli_begin_transaction($connection);

    // ✅ Insert payment into `payments` table
    $queryPayment = "INSERT INTO payments (BillingID, PaymentAmount, PaymentDate, PaymentMethod) 
                     VALUES (?, ?, ?, ?)";
    $stmtPayment = mysqli_prepare($connection, $queryPayment);
    mysqli_stmt_bind_param($stmtPayment, "sdss", $billingID, $amount, $paymentDate, $paymentMethod);

    if (!mysqli_stmt_execute($stmtPayment)) {
        throw new Exception("Error saving payment: " . mysqli_error($connection));
    }

    // ✅ Get total amount paid
    $queryTotalPaid = "SELECT SUM(PaymentAmount) FROM payments WHERE BillingID = ?";
    $stmtTotalPaid = mysqli_prepare($connection, $queryTotalPaid);
    mysqli_stmt_bind_param($stmtTotalPaid, "s", $billingID);
    mysqli_stmt_execute($stmtTotalPaid);
    mysqli_stmt_bind_result($stmtTotalPaid, $totalPaid);
    mysqli_stmt_fetch($stmtTotalPaid);
    mysqli_stmt_close($stmtTotalPaid);

    // ✅ Get total fee
    $queryTotalFee = "SELECT TotalFee, PaymentType FROM appointmentbilling WHERE BillingID = ?";
    $stmtTotalFee = mysqli_prepare($connection, $queryTotalFee);
    mysqli_stmt_bind_param($stmtTotalFee, "s", $billingID);
    mysqli_stmt_execute($stmtTotalFee);
    mysqli_stmt_bind_result($stmtTotalFee, $totalFee, $currentPaymentMethod);
    mysqli_stmt_fetch($stmtTotalFee);
    mysqli_stmt_close($stmtTotalFee);

    // ✅ If first payment was cash but now is card, update it
    if (strtolower($currentPaymentMethod) == "cash") {
        $updatePaymentMethod = "UPDATE appointmentbilling SET PaymentType = 'Card' WHERE BillingID = ?";
        $stmtUpdateMethod = mysqli_prepare($connection, $updatePaymentMethod);
        mysqli_stmt_bind_param($stmtUpdateMethod, "s", $billingID);
        mysqli_stmt_execute($stmtUpdateMethod);
        mysqli_stmt_close($stmtUpdateMethod);
    }

    // ✅ Update PaymentStatus: "paid" or "partial"
    if ($totalPaid >= $totalFee) {
        $updateBilling = "UPDATE appointmentbilling SET PaymentStatus = 'paid' WHERE BillingID = ?";
    } else {
        $updateBilling = "UPDATE appointmentbilling SET PaymentStatus = 'partial' WHERE BillingID = ?";
    }
    
    $stmtUpdate = mysqli_prepare($connection, $updateBilling);
    mysqli_stmt_bind_param($stmtUpdate, "s", $billingID);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);

    mysqli_commit($connection);
    $response = ['status' => 'success', 'message' => 'Payment successful!'];

} catch (Exception $e) {
    mysqli_rollback($connection);
    error_log("Error processing payment: " . $e->getMessage()); // Log error
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response); // ✅ Ensure JSON response always
exit;
?>
