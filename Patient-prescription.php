<?php 
include 'php/connection.php';
session_start();

if (!isset($_SESSION['PatientID'])) {
    header("Location: login.php");
    exit;
}

$patientID = $_SESSION['PatientID'];
$firstname = $_SESSION['Firstname'];
$lastname = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// Handle search query
$searchQuery = "";
$searchCondition = "";
$searchParams = [];

if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchQuery = trim($_POST['search']);
    $searchCondition = " AND (d.Firstname LIKE ? OR d.Middlename LIKE ? OR d.Lastname LIKE ? 
                             OR CONCAT(d.Firstname, ' ', d.Lastname) LIKE ? 
                             OR d.Specialization LIKE ? 
                             OR p.Notes LIKE ?)";
    $searchParam = "%" . $searchQuery . "%";
    $searchParams = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
}

// Fetch prescriptions with optional search filter
$query = "SELECT p.*, d.Firstname AS DoctorFirst, d.Middlename AS DoctorMiddle, d.Lastname AS DoctorLast, d.Specialization 
          FROM prescription p 
          JOIN dentist d ON p.DentistID = d.DentistID 
          WHERE p.PatientID = ? $searchCondition 
          ORDER BY p.created_at DESC";

$stmt = $connection->prepare($query);

if (!empty($searchQuery)) {
    $stmt->bind_param("s" . str_repeat("s", count($searchParams)), $patientID, ...$searchParams);
} else {
    $stmt->bind_param("s", $patientID);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="css/patientstyle.css">
    <link rel="stylesheet" href="css/prescription.css">
    <title>Prescription Records</title>
</head>
<body> 
    <div id="wrapper">

        <div id="left_panel">
            <img id="logo" src="icons/patient/logo_seek4smiles.png" alt="Logo">
            <label><a href="Patient-Homepage.php"><img src="icons/patient/home_icon.png"> Homepage</a></label>
            <label><a href="Patient-notification.php"><img src="icons/patient/notification_icon.png"> Notifications</a></label>
            <label><a href="Patient-record.php"><img src="icons/patient/medical_record_icon.png"> Medical Records</a></label>
            <label><a href="Patient-prescription.php"><img src="icons/patient/prescription.png"> Prescription Records</a></label>
            <label><a href="Patient-appointment.php"><img src="icons/patient/calendar_icon.png"> Appointments</a></label>
            <label><a href="Patient-message.php"><img src="icons/patient/message_icon.png"> Messages</a></label>
            <label><a href="Patient-billing.php"><img src="icons/patient/billing_icons.png"> Billing</a></label>
            <label><a href="Patient-profile.php"><img src="icons/patient/profile_icon.png"> Profile</a></label>
            <label><a href="Logout.php"><img src="icons/patient/signout_icon.png"> Sign Out</a></label>
        </div>

        <div id="right_panel">
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname) ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Profile Icon">
            </div>

            <div class="preception-container active-container">
                <div class="record-p">
                    <p>Prescription Records</p>
                </div>

                <form method="POST">
                    <div class="medical-search-wrapper">
                        <div class="medical-search-field">
                            <img src="icons/patient/search_icon.png" alt="Search Icon" class="medical-search-icon" width="20" height="20">
                            <input type="text" name="search" class="medical-search-input" placeholder="Search by doctor name, specialization, or medication"
                                value="<?php echo htmlspecialchars($searchQuery); ?>">
                        </div>
                        <button type="submit" class="medical-search-button">Search</button>        
                    </div>
                </form>

                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // For each prescription, fetch only one (the first) medicine entry.
                        $pID = $row['PrescriptionID'];
                        $medicine_query = "SELECT * FROM prescription_medicines WHERE PrescriptionID = '$pID' LIMIT 1";
                        $medicine_result = mysqli_query($connection, $medicine_query);
                        $medicine = mysqli_fetch_assoc($medicine_result);
                ?>
                <div class="medication-card">
                    <div class="card-header">
                        <h2>Medication</h2>
                        <div class="last-updated">
                            <span>Last Updated:</span>
                            <span><?php echo date("F d, Y", strtotime($row['created_at'])); ?></span>
                        </div>
                    </div>

                    <div class="medication-details">
                        <div class="details-column">
                            <?php if ($medicine): ?>
                            <div class="detail-item">
                                <span class="label">Medication:</span>
                                <span class="value"><?php echo htmlspecialchars($medicine['Medicine']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Dosage:</span>
                                <span class="value"><?php echo htmlspecialchars($medicine['Dosage']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Instructions:</span>
                                <span class="value"><?php echo htmlspecialchars($medicine['Instructions']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Refill Status:</span>
                                <span class="value"><?php echo htmlspecialchars($medicine['RefillStatus']); ?></span>
                            </div>
                            <?php else: ?>
                            <p>No medicine entry found.</p>
                            <?php endif; ?>
                        </div>

                        <div class="details-column">
                            <div class="detail-item">
                                <span class="label">Prescribed:</span>
                                <span class="value"><?php echo date("F d, Y", strtotime($row['PrescriptionDate'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Prescription ID:</span>
                                <span class="value"><?php echo htmlspecialchars($row['PrescriptionID']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="patient-details">
                        <div class="details-column">
                            <div class="detail-item">
                                <span class="label">Patient:</span>
                                <span class="value"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label">Doctor:</span>
                                <span class="value"><?php echo htmlspecialchars($row['DoctorFirst'] . (!empty($row['DoctorMiddle']) ? " " . $row['DoctorMiddle'] : "") . " " . $row['DoctorLast']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="notes-section">
                        <span class="label">Notes:</span>
                        <span class="value italic"><?php echo htmlspecialchars($row['Notes']); ?></span>
                    </div>

                    <div class="button-group">
                        <a href="prescription_generate_pdf.php?prescription_id=<?php echo urlencode($row['PrescriptionID']); ?>" target="_blank">
                            <button class="btn btn-Download">View as PDF</button>
                        </a>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p>No prescriptions found.</p>";
                }
                ?>
            </div>
        </div>
    </div>   
</body>
</html>
