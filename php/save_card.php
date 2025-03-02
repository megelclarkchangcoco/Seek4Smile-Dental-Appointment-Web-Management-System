<?php
header('Content-Type: application/json'); // Ensure JSON output
include 'connection.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['PatientID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$patientID = $_SESSION['PatientID'];
$cardType = $_POST['cardType'] ?? null;
$cardName = $_POST['cardName'] ?? null;
$cardNumber = $_POST['cardNumber'] ?? null;
$expiryDate = $_POST['expiryDate'] ?? null;
$cvv = $_POST['cvv'] ?? null;

// Check for missing fields
if (!$cardType || !$cardName || !$cardNumber || !$expiryDate || !$cvv) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

// ✅ Check if the card number already exists (ignore CVV)
$checkCardQuery = "SELECT CardID FROM card WHERE CardNumber = ?";
$stmtCheck = mysqli_prepare($connection, $checkCardQuery);
mysqli_stmt_bind_param($stmtCheck, "s", $cardNumber);
mysqli_stmt_execute($stmtCheck);
mysqli_stmt_store_result($stmtCheck);

if (mysqli_stmt_num_rows($stmtCheck) > 0) {
    echo json_encode(['status' => 'error', 'message' => 'This card is already registered.']);
    exit;
}
mysqli_stmt_close($stmtCheck);

// ✅ Insert into `card` table
$query = "INSERT INTO card (PatientID, CardType, NameOnCard, CardNumber, ExpiryDate, CVV) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "ssssss", $patientID, $cardType, $cardName, $cardNumber, $expiryDate, $cvv);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['status' => 'success', 'message' => 'Card saved successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($connection)]);
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
