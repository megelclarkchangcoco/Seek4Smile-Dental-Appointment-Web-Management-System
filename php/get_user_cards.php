<?php
header('Content-Type: application/json');
include 'connection.php';
session_start();

if (!isset($_SESSION['PatientID'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$patientID = $_SESSION['PatientID'];

$query = "SELECT CardID, CardType, CardNumber FROM card WHERE PatientID = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, "s", $patientID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cards = [];
while ($row = mysqli_fetch_assoc($result)) {
    $maskedCardNumber = "•••• •••• •••• " . substr($row['CardNumber'], -4); // Masked card number
    $cards[] = [
        'CardID' => $row['CardID'],
        'CardType' => $row['CardType'],
        'CardNumber' => $maskedCardNumber
    ];
}

mysqli_stmt_close($stmt);
mysqli_close($connection);

echo json_encode(['status' => 'success', 'cards' => $cards]);
?>
