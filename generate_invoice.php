<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load MPDF library
include 'php/connection.php'; // Include database connection

session_start();

// Check if patient is logged in
if (!isset($_SESSION['PatientID'])) {
    die("User not logged in.");
}

$patientID = $_SESSION['PatientID'];
$billingID = $_GET['billing_id'] ?? null;

if (!$billingID) {
    die("No Billing ID provided.");
}

//  Fetch Patient Details
$queryPatient = "SELECT Firstname, Middlename, Lastname, ContactDetails, 
                        HouseNumberStreet, Barangay, CityMunicipality, Email
                 FROM patient WHERE PatientID = ?";
$stmt = $connection->prepare($queryPatient);
$stmt->bind_param("s", $patientID);
$stmt->execute();
$result = $stmt->get_result();
$patientData = $result->fetch_assoc();
$stmt->close();

// Format patient name and address
$patientFullName = $patientData['Firstname'] . " " . 
                   (!empty($patientData['Middlename']) ? $patientData['Middlename'] . " " : "") . 
                   $patientData['Lastname'];

$patientAddress = $patientData['HouseNumberStreet'] . ", " . 
                  $patientData['Barangay'] . ", " . 
                  $patientData['CityMunicipality'];

$contactDetails = $patientData['ContactDetails'];
$patientEmail = $patientData['Email'];

//  Fetch Billing and Payment Details
$query = "SELECT ab.BillingID, ab.TotalFee, 
                 a.AppointmentID, a.AppointmentDate, a.AppointmentStatus, 
                 a.AppointmentProcedure, a.AppointmentTreatment, a.AppointmentLaboratory, 
                 d.Firstname AS DentistFirst, d.Lastname AS DentistLast
          FROM appointmentbilling ab
          JOIN appointment a ON ab.AppointmentID = a.AppointmentID
          JOIN dentist d ON a.DentistID = d.DentistID
          WHERE ab.BillingID = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param("s", $billingID);
$stmt->execute();
$result = $stmt->get_result();
$billingData = $result->fetch_assoc();
$stmt->close();

if (!$billingData) {
    die("No billing record found.");
}

//  Fetch All Payments for Installments
$queryPayments = "SELECT PaymentAmount, PaymentDate FROM payments WHERE BillingID = ? ORDER BY PaymentDate ASC";
$stmtPayments = $connection->prepare($queryPayments);
$stmtPayments->bind_param("s", $billingID);
$stmtPayments->execute();
$resultPayments = $stmtPayments->get_result();
$stmtPayments->close();

// Store payment history in table format
$paymentDetails = "";
$paidAmount = 0; // Total paid amount

while ($paymentRow = $resultPayments->fetch_assoc()) {
    $paidAmount += floatval($paymentRow['PaymentAmount']); // Sum all payments
    $paymentDetails .= "<tr>
        <td>" . date("F d, Y", strtotime($paymentRow['PaymentDate'])) . "</td>
        <td>P" . number_format($paymentRow['PaymentAmount'], 2) . "</td>
    </tr>";
}

//  Generate Invoice ID
$invoiceID = "INV" . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

//  Assign variables for Invoice
$appointmentDate = date("F d, Y", strtotime($billingData['AppointmentDate']));
$doctorFullName = "Dr. " . $billingData['DentistFirst'] . " " . $billingData['DentistLast'];

//  Check for Penalty
$servicesPerformed = trim($billingData['AppointmentProcedure'] . " " . $billingData['AppointmentTreatment'] . " " . $billingData['AppointmentLaboratory']);
if (strtolower($billingData['AppointmentStatus']) == 'penalty') {
    $servicesPerformed = "Penalty";
}

//  Invoice Calculations
$subtotal = floatval($billingData['TotalFee']);
$remainingBalance = max($subtotal - $paidAmount, 0); // Prevent negative balance

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
            <img src='" . __DIR__ . "/icons/patient/invoicelogo2.png' style='width: 200px; height: auto; float: right;' alt='Logo'>
            <h1>MEDICAL RECEIPT</h1>
        </div>
        <div class='invoice-details'>
            <table>
                <tr><td><b>Bill To:</b></td><td>$patientFullName</td></tr>   
                <tr><td><b>Recipt Number:</b></td><td>$invoiceID</td></tr>
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
                <thead>
                    <tr>
                        <th>SERVICE DATE</th>
                        <th>SERVICES PERFORMED</th>
                        <th>FEE</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>$appointmentDate</td>
                        <td>$servicesPerformed</td>
                        <td>P" . number_format($subtotal, 2) . "</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class='payment-summary-row clearfix'>
            <div class='payment-history'>
                <h3>Payment</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Payment Date</th>
                            <th>Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>
                        $paymentDetails
                    </tbody>
                </table>
            </div>
            
            <div class='total-summary'>
            <h3>History</h3>
                <table>
                    <tr>
                        <td>Total Due:</td>
                        <td>P" . number_format($subtotal, 2) . "</td>
                    </tr>
                    <tr>
                        <td>Total Paid:</td>
                        <td>P" . number_format($paidAmount, 2) . "</td>
                    </tr>
                    <tr>
                        <td>Remaining Balance:</td>
                        <td>P" . number_format($remainingBalance, 2) . "</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>";

// Generate PDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output();
