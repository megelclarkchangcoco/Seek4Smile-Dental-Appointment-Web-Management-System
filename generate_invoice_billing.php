<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load MPDF library
include 'php/connection.php'; // Include database connection

// Get Billing ID from URL
if (!isset($_GET['billingID'])) {
    die("No Billing ID provided.");
}

$billingID = mysqli_real_escape_string($connection, $_GET['billingID']);

// Fetch billing details
$query = "
    SELECT 
        pb.BillingID, 
        p.Firstname, 
        p.Middlename, 
        p.Lastname, 
        p.ContactDetails, 
        p.HouseNumberStreet, 
        p.Barangay, 
        p.CityMunicipality, 
        p.Email, 
        a.AppointmentType, 
        pb.TotalFee, 
        pb.CreatedAt, 
        pb.PaymentStatus, 
        a.AppointmentProcedure, 
        a.AppointmentTreatment, 
        a.AppointmentLaboratory, 
        d.Firstname AS DentistFirst, 
        d.Lastname AS DentistLast
    FROM appointmentbilling pb
    JOIN appointment a ON pb.AppointmentID = a.AppointmentID
    JOIN patient p ON pb.PatientID = p.PatientID
    JOIN dentist d ON a.DentistID = d.DentistID
    WHERE pb.BillingID = '$billingID'
";

$result = mysqli_query($connection, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Invoice not found.");
}

$row = mysqli_fetch_assoc($result);

// Format patient details
$patientFullName = $row['Firstname'] . " " . 
                   (!empty($row['Middlename']) ? $row['Middlename'] . " " : "") . 
                   $row['Lastname'];

$patientAddress = $row['HouseNumberStreet'] . ", " . $row['Barangay'] . ", " . $row['CityMunicipality'];
$contactDetails = $row['ContactDetails'];
$patientEmail = $row['Email'];
$appointmentDate = date("F d, Y", strtotime($row['CreatedAt']));
$totalAmount = number_format($row['TotalFee'], 2);

// Generate Invoice ID
$invoiceID = "INV" . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

// Fetch payment details
$queryPayments = "SELECT PaymentAmount, PaymentDate FROM payments WHERE BillingID = ? ORDER BY PaymentDate ASC";
$stmtPayments = $connection->prepare($queryPayments);
$stmtPayments->bind_param("s", $billingID);
$stmtPayments->execute();
$resultPayments = $stmtPayments->get_result();
$stmtPayments->close();

// Store payment history in table format
$paymentDetails = "";
$paidAmount = 0;

while ($paymentRow = $resultPayments->fetch_assoc()) {
    $paidAmount += floatval($paymentRow['PaymentAmount']); // Sum payments
    $paymentDetails .= "<tr>
        <td>" . date("F d, Y", strtotime($paymentRow['PaymentDate'])) . "</td>
        <td>P" . number_format($paymentRow['PaymentAmount'], 2) . "</td>
    </tr>";
}

// Check for Penalty
$servicesPerformed = trim($row['AppointmentProcedure'] . " " . $row['AppointmentTreatment'] . " " . $row['AppointmentLaboratory']);
if (strtolower($row['AppointmentStatus']) == 'penalty') {
    $servicesPerformed = "Penalty";
}

// Invoice Calculations
$subtotal = floatval($row['TotalFee']);
$remainingBalance = max($subtotal - $paidAmount, 0); // Prevent negative balance

// Doctor's Name
$doctorFullName = "Dr. " . $row['DentistFirst'] . " " . $row['DentistLast'];

//  HTML Template for Invoice
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link rel='stylesheet' href='css/styleinvoicetemplate.css'>
    <title>Medical Invoice</title>
</head>
<body>

<div class='invoice-container'>
    <div class='invoice-header'>
        <img src='icons/patient/invoicelogo2.png' style='width: 200px; float: right;' alt='Logo'>
        <h1>MEDICAL RECEIPT</h1>
    </div>
    
    <div class='invoice-details'>
        <table>
            <tr><td><b>Bill To:</b></td><td>$patientFullName</td></tr>   
            <tr><td><b>Receipt Number:</b></td><td>$invoiceID</td></tr>
            <tr><td><b>Patient Address:</b></td><td>$patientAddress</td></tr>
            <tr><td><b>ADM Date:</b></td><td>$appointmentDate</td></tr>
            <tr><td><b>Phone:</b></td><td>$contactDetails</td></tr>
            <tr><td><b>Email:</b></td><td>$patientEmail</td></tr>
            <tr><td><b>Physician:</b></td><td>$doctorFullName</td></tr>
        </table>
    </div>

    <div class='invoice-services'>
        <h3>Services Performed</h3>
        <table>
            <thead><tr><th>SERVICE DATE</th><th>SERVICES PERFORMED</th><th>FEE</th></tr></thead>
            <tbody><tr><td>$appointmentDate</td><td>$servicesPerformed</td><td>P" . number_format($subtotal, 2) . "</td></tr></tbody>
        </table>
    </div>

    <div class='payment-summary-row clearfix'>        
        <div class='total-summary'>
            <h3>Payment Summary</h3>
            <table>
                <tr><td>Total Due:</td><td>P" . number_format($subtotal, 2) . "</td></tr>
                <tr><td>Total Paid:</td><td>P" . number_format($paidAmount, 2) . "</td></tr>
                <tr><td>Remaining Balance:</td><td>P" . number_format($remainingBalance, 2) . "</td></tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>";

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Invoice-$billingID.pdf", "I");
