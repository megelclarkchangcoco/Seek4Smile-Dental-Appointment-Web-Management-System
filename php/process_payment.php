<?php
include 'connection.php';

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

$billingID = $data['billingID'];
$amount = $data['amount'];
$paymentStatus = $data['paymentStatus'];

// Insert payment record
$insertPayment = "INSERT INTO payments (BillingID, PaymentAmount, PaymentDate, PaymentMethod) VALUES ('$billingID', '$amount', NOW(), 'Cash')";

// Update billing status
$updateBilling = "UPDATE appointmentbilling SET PaymentStatus = '$paymentStatus' WHERE BillingID = '$billingID'";

if (mysqli_query($connection, $insertPayment) && mysqli_query($connection, $updateBilling)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => mysqli_error($connection)]);
}

mysqli_close($connection);
?>
