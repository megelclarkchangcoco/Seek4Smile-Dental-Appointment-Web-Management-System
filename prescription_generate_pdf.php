<?php
require_once __DIR__ . '/vendor/autoload.php'; // Load MPDF library
include 'php/connection.php'; // Include database connection

if (!isset($_GET['prescription_id'])) {
    die("No prescription ID provided.");
}

$prescriptionID = $_GET['prescription_id'];

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch prescription data along with Dentist and Patient info
$query = "
    SELECT p.*, 
           d.Firstname AS DoctorFirst, d.Middlename AS DoctorMiddle, d.Lastname AS DoctorLast, 
           d.Email AS DoctorEmail, d.esignature AS DoctorSignature, 
           pt.Firstname AS PatientFirst, pt.Middlename AS PatientMiddle, pt.Lastname AS PatientLast, 
           pt.Age, pt.Sex 
    FROM prescription p
    JOIN dentist d ON p.DentistID = d.DentistID
    JOIN patient pt ON p.PatientID = pt.PatientID
    WHERE p.PrescriptionID = ?
";
$stmt = $connection->prepare($query);
if (!$stmt) {
    die("Query preparation failed: " . $connection->error);
}
$stmt->bind_param("s", $prescriptionID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    die("Prescription not found.");
}
$prescription = $result->fetch_assoc();
$stmt->close();

// Format doctor & patient names (handling missing middlename)
$doctorFullName = $prescription['DoctorFirst'] . (!empty($prescription['DoctorMiddle']) ? " " . $prescription['DoctorMiddle'] : "") . " " . $prescription['DoctorLast'];
$patientFullName = $prescription['PatientFirst'] . (!empty($prescription['PatientMiddle']) ? " " . $prescription['PatientMiddle'] : "") . " " . $prescription['PatientLast'];

// Fetch all medicine entries for this prescription
$medicines = [];
$medicine_query = "SELECT * FROM prescription_medicines WHERE PrescriptionID = ?";
$stmt2 = $connection->prepare($medicine_query);
$stmt2->bind_param("s", $prescriptionID);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($med = $result2->fetch_assoc()) {
    $medicines[] = $med;
}
$stmt2->close();

// Prepare HTML content for PDF
$html = "
<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='css/styleprescription.css'>
    <style>
      /* Add minimal styles for the PDF here if needed */
      .signature-image { width: 150px; }
      .medication { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class='prescription-container'>
        <div class='prescription-header'>
            <div class='doctor-info'>
                <div class='clinic-logo'>
                    <img src='icons/patient/prescriptionlogo.png' style='width: 200px; height: auto;' alt='Clinic Logo'>
                </div>
                <div class='doctor-name'>{$doctorFullName}, DMD</div>
                <div class='doctor-details'>
                    Specialization: Periodontics<br>
                    Email: {$prescription['DoctorEmail']}<br>
                    Fellow: Philippine Academy of Dental Professionals (PAGP)<br>
                    Member: International Society of Oral Health Specialists (ISOHS)
                </div>
            </div>
        </div>

        <div class='patient-info'>
            <div class='patient-field'><span class='patient-label'>Name:</span><span class='patient-value'>{$patientFullName}</span></div>
            <div class='patient-field'><span class='patient-label'>Date:</span><span class='patient-value'>" . date("F d, Y", strtotime($prescription['PrescriptionDate'])) . "</span></div>
            <div class='patient-field'><span class='patient-label'>Age:</span><span class='patient-value'>{$prescription['Age']}</span>
            <span class='patient-label'>Sex:</span><span class='patient-value'>{$prescription['Sex']}</span></div>
        </div>

        <div class='prescription-header-icons'>
            <div class='rx-symbol'>℞</div>
            <div class='title'>Medication</div>
        </div>

        <div class='medication-list'>";
        
        $counter = 1;
        foreach ($medicines as $med) {
            $html .= "
            <div class='medication'>
                <div class='medication-name'>{$counter}. " . htmlspecialchars($med['Medicine']) . "</div>
                <ul class='medication-details'>
                    <li>Dosage: " . htmlspecialchars($med['Dosage']) . "</li>
                    <li>Instructions: " . htmlspecialchars($med['Instructions']) . "</li>
                    <li>Refill Status: " . htmlspecialchars($med['RefillStatus']) . "</li>
                </ul>
            </div>";
            $counter++;
        }
$html .= "
        </div>

        <div class='follow-up'>
            <strong>Notes:</strong>
            <p>" . htmlspecialchars($prescription['Notes']) . "</p>
        </div>

        <div class='signature'>
            " . (!empty($prescription['DoctorSignature']) ? "<img src='" . $prescription['DoctorSignature'] . "' class='signature-image'>" : "<span>No Signature Available</span>") . "
            <div class='signature-line'>{$doctorFullName}, DMD</div>
        </div>
    </div>
</body>
</html>
";


$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('Prescription_' . $prescriptionID . '.pdf', 'I'); // 'I' displays inline in browser
?>