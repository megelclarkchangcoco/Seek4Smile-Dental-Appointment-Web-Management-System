<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load MPDF library
include 'php/connection.php'; // Include database connection

session_start();

if (!isset($_GET['billingID'])) {
    die("No Billing ID provided.");
}

$billingID = mysqli_real_escape_string($connection, $_GET['billingID']);

// Fetch Billing and Payment Details
$query = "
    SELECT 
        pb.BillingID, 
        pb.PatientID, 
        p.Firstname, 
        p.Lastname, 
        p.ContactDetails, 
        CONCAT(p.HouseNumberStreet, ', ', p.Barangay, ', ', p.CityMunicipality) AS patientAddress, 
        p.Email AS patientEmail, 
        a.AppointmentDate, 
        a.AppointmentType, 
        COALESCE(a.AppointmentLaboratory, a.AppointmentProcedure, a.AppointmentTreatment) AS servicesPerformed, 
        pb.TotalFee, 
        pb.PaymentStatus,
        d.Firstname AS DentistFirst, 
        d.Lastname AS DentistLast,
        COALESCE(SUM(pay.PaymentAmount), 0) AS paidAmount,
        (pb.TotalFee - COALESCE(SUM(pay.PaymentAmount), 0)) AS remainingBalance,
        GROUP_CONCAT(CONCAT(pay.PaymentDate, ' - P', FORMAT(pay.PaymentAmount, 2)) SEPARATOR '<br>') AS paymentDetails
    FROM appointmentbilling pb
    JOIN appointment a ON pb.AppointmentID = a.AppointmentID
    JOIN patient p ON pb.PatientID = p.PatientID
    JOIN dentist d ON a.DentistID = d.DentistID
    LEFT JOIN payments pay ON pb.BillingID = pay.BillingID
    WHERE pb.BillingID = '$billingID'
    GROUP BY pb.BillingID
";

$result = mysqli_query($connection, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("Billing details not found.");
}

$data = mysqli_fetch_assoc($result);

// Extract data
$invoiceID = "INV" . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
$patientFullName = $data['Firstname'] . " " . $data['Lastname'];
$patientAddress = $data['patientAddress'];
$contactDetails = $data['ContactDetails'];
$patientEmail = $data['patientEmail'];
$appointmentDate = date("F d, Y", strtotime($data['AppointmentDate']));
$servicesPerformed = $data['servicesPerformed'];
$subtotal = $data['TotalFee'];
$paidAmount = $data['paidAmount'];
$remainingBalance = max($subtotal - $paidAmount, 0); // Prevent negative balance
$doctorFullName = "Dr. " . $data['DentistFirst'] . " " . $data['DentistLast'];
$paymentDetails = $data['paymentDetails'] ?: "No payments made";

//  Invoice Calculations
$subtotal = floatval($billingData['TotalFee']);
$remainingBalance = max($subtotal - $paidAmount, 0); // Prevent negative balance

// Generate Invoice HTML
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Medical Invoice</title>
    <style>
    body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
            }
            .invoice-container {
                max-width: 800px;
                margin: 0 auto;
                border: 1px solid #ccc;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .invoice-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            .invoice-header h1 {
                margin: 0;
                font-size: 24px;
            }
            .invoice-header img {
                max-height: 50px;
            }
            .invoice-details {
                margin-bottom: 20px;
            }
            .invoice-details table {
                width: 100%;
                border-collapse: collapse;
            }
            .invoice-details table td {
                padding: 8px;
                border: 1px solid #ccc;
            }
            .invoice-details table td:first-child {
                font-weight: bold;
                width: 30%;
            }
            .invoice-services {
                margin-bottom: 20px;
            }
            .invoice-services table {
                width: 100%;
                border-collapse: collapse;
            }
            .invoice-services table th,
            .invoice-services table td {
                padding: 10px;
                border: 1px solid #ccc;
                text-align: left;
            }
            .invoice-services table th {
                background-color: #f4f4f4;
            }
                    .payment-summary-row {
                width: 100%;
                margin-top: 20px;
            }
            
            .payment-history {
                width: 45%;
                float: left;
            }
            
            .payment-history table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .payment-history th,
            .payment-history td {
                border: 1px solid #ccc;
                padding: 8px;
                text-align: left;
            }
            
            .payment-history th {
                background-color: #f4f4f4;
            }
            
            .total-summary {
                width: 45%;
                float: right;
            }
            
            .total-summary table {
                width: 100%;
                border-collapse: collapse;
            }
            
            .total-summary td {
                padding: 8px;
                border: none;
            }
            
            .total-summary td:first-child {
                text-align: left;
                font-weight: bold;
            }
            
            .total-summary td:last-child {
                text-align: right;
            }
            
            /* Clear float */
            .clearfix::after {
                content: '';
                display: table;
                clear: both;
            }
    </style>
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
                <tr><td><b>Invoice Number:</b></td><td>$invoiceID</td></tr>
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
        <div class='clearfix'>
            <div class='payment-history' style='width: 50%; float: left;'>
                <h3>Payment History</h3>
                <table>
                    <thead><tr><th>Payment Date</th><th>Amount Paid</th></tr></thead>
                    <tbody><tr><td colspan='2'>$paymentDetails</td></tr></tbody>
                </table>
            </div>
            <div class='total-summary' style='width: 50%; float: right;'>
                <h3>Billing Summary</h3>
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

// Generate PDF using MPDF
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Invoice_$invoiceID.pdf", "I"); // View in browser
?>
