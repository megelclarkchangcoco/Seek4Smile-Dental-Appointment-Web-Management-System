<?php 
include 'php/connection.php';
session_start();

if (!isset($_SESSION['DentistID'])) {
    header("Location: login.php");
    exit;
}

$firstname   = $_SESSION['Firstname'];
$lastname    = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// Fetch all patients
$patientQuery  = "SELECT * FROM patient";
$patientResult = mysqli_query($connection, $patientQuery);

// Fetch latest appointment for each patient
$appointmentQuery = "
    SELECT p.PatientID, a.AppointmentType, a.AppointmentDate, 
           d.Firstname AS DentistFirst, d.Lastname AS DentistLast 
    FROM patient p
    LEFT JOIN appointment a ON p.PatientID = a.PatientID AND a.AppointmentStatus = 'Completed'
    LEFT JOIN dentist d ON a.DentistID = d.DentistID
    ORDER BY a.AppointmentDate DESC
";
$appointmentResult = mysqli_query($connection, $appointmentQuery);
$appointments      = [];
while ($row = mysqli_fetch_assoc($appointmentResult)) {
    $appointments[$row['PatientID']] = $row;
}

// Handle view button click
$selectedPatient = null;
if (isset($_GET['view'])) {
    $patientID = $_GET['view'];
    $selectedPatientQuery  = "SELECT * FROM patient WHERE PatientID = '$patientID'";
    $selectedPatientResult = mysqli_query($connection, $selectedPatientQuery);
    $selectedPatient       = mysqli_fetch_assoc($selectedPatientResult);
    $selectedPatient['img'] = !empty($selectedPatient['img']) ? $selectedPatient['img'] : 'img/user_default.png';
    
    // Fetch total completed appointments for selected patient
    $completedAppointmentsQuery  = "SELECT COUNT(*) AS totalCompleted FROM appointment WHERE AppointmentStatus = 'Completed' AND PatientID = '$patientID'";
    $completedAppointmentsResult = mysqli_query($connection, $completedAppointmentsQuery);
    $completedAppointmentsData   = mysqli_fetch_assoc($completedAppointmentsResult);
    $totalCompletedAppointments  = $completedAppointmentsData['totalCompleted'] ?? 0;
    
    // Fetch total penalty appointments for selected patient
    $penaltyAppointmentsQuery  = "SELECT COUNT(*) AS totalPenalty FROM appointment WHERE AppointmentStatus = 'Penalty' AND PatientID = '$patientID'";
    $penaltyAppointmentsResult = mysqli_query($connection, $penaltyAppointmentsQuery);
    $penaltyAppointmentsData   = mysqli_fetch_assoc($penaltyAppointmentsResult);
    $totalPenaltyAppointments  = $penaltyAppointmentsData['totalPenalty'] ?? 0;
    
    // Fetch the last completed appointment for selected patient
    $lastCompletedAppointmentQuery = "
        SELECT a.AppointmentDate, a.TimeStart, a.TimeEnd, a.AppointmentType, 
               d.Firstname AS DentistFirst, d.Lastname AS DentistLast, d.Specialization
        FROM appointment a
        JOIN dentist d ON a.DentistID = d.DentistID
        WHERE a.PatientID = '$patientID' AND a.AppointmentStatus = 'Completed'
        ORDER BY a.AppointmentDate DESC, a.TimeStart DESC, a.TimeEnd DESC
        LIMIT 1
    ";
    $lastCompletedAppointmentResult = mysqli_query($connection, $lastCompletedAppointmentQuery);
    if (!$lastCompletedAppointmentResult) {
        die("Error fetching last completed appointment: " . mysqli_error($connection));
    }
    $lastCompletedAppointment = mysqli_fetch_assoc($lastCompletedAppointmentResult);
    
    // Fetch appointment history for selected patient
    $appointmentsHistoryQuery = "
        SELECT a.AppointmentStatus, a.AppointmentDate, a.TimeStart, a.TimeEnd, 
               a.AppointmentType, a.Reason, d.Firstname AS DentistFirst, d.Lastname AS DentistLast, 
               ab.PaymentType, ab.PaymentStatus
        FROM appointment a
        JOIN dentist d ON a.DentistID = d.DentistID
        LEFT JOIN appointmentbilling ab ON a.AppointmentID = ab.AppointmentID
        WHERE a.PatientID = '$patientID'
        ORDER BY a.AppointmentDate DESC
    ";
    $appointmentsHistoryResult = mysqli_query($connection, $appointmentsHistoryQuery);
    if (!$appointmentsHistoryResult) {
        die("Error fetching appointments: " . mysqli_error($connection));
    }
    
    // --- Ensure PrescriptionID is populated ---
    mysqli_query($connection, "UPDATE prescription SET PrescriptionID = CONCAT('PREID', LPAD(id, 4, '0')) WHERE PrescriptionID IS NULL OR PrescriptionID = ''");
    
    // New prescription query joining normalized tables
    $prescriptionQuery = "
        SELECT 
          p.PrescriptionID, 
          p.PrescriptionDate, 
          p.Notes, 
          p.created_at,
          d.Firstname AS DoctorFirst, 
          d.Middlename AS DoctorMiddle, 
          d.Lastname AS DoctorLast,
          pm.Medicine, 
          pm.Dosage, 
          pm.Instructions, 
          pm.RefillStatus
        FROM prescription p
        JOIN dentist d ON p.DentistID = d.DentistID
        JOIN prescription_medicines pm ON p.PrescriptionID = pm.PrescriptionID
        WHERE p.PatientID = '$patientID'
        ORDER BY p.PrescriptionDate DESC
    ";
    $prescriptionResult = mysqli_query($connection, $prescriptionQuery);
    if (!$prescriptionResult) {
        die("Prescription query failed: " . mysqli_error($connection));
    }
    
    // Fetch medical records for selected patient
    $medicalRecordQuery = "
        SELECT id, subject, filename, timeSubmitted, dateSubmitted
        FROM medicalrecord
        WHERE PatientID = '$patientID'
        ORDER BY dateSubmitted DESC, timeSubmitted DESC
    ";
    $medicalRecordResult = mysqli_query($connection, $medicalRecordQuery);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link to CSS-->
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <link rel="stylesheet" href="css/dentistpatient.css">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <title>Dentist - Patients</title>
    <!-- Google Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body> 
    <!-- Main wrapper -->   
    <div id="wrapper">

        <!-- Left panel -->
        <div id="left_panel">
            <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo">
            <label>
                <a href="Dentist-homepage.php">
                    <img src="icons/dentalassistant/home_icon.png" alt="Dashboard"> Dashboard
                </a>
            </label>
            <label>
                <a href="Dentist-notification.php">
                    <img src="icons/dentalassistant/notif_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Dentist-patient.php">
                    <img src="icons/dentalassistant/patient_icon.png" alt="Patients"> Patients
                </a>
            </label>
            <label>
                <a href="Dentist-perscription.php">
                    <img src="icons/patient/prescription.png" alt="Prescription"> Prescription
                </a>
            </label>
            <label>
                <a href="Dentist-appointment.php">
                    <img src="icons/dentist/calendar_icon.png" alt="Calendar"> Calendar
                </a> 
            </label>
            <label>
                <a href="Dentist-message.php">
                    <img src="icons/patient/message_icon.png" alt="Messages"> Messages
                </a> 
            </label>
            <label>
                <a href="Dentist-profile.php">
                    <img src="icons/dentist/profile_icon.png" alt="Profile"> Profile
                </a> 
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/dentalassistant/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <!-- Right panel -->
        <div id="right_panel">
            <!-- Header with profile info -->
            <div id="header_with_selection">
                <div class="sub-navigation" style="margin: 20px">
                    <a href="Dentist-patient.php">Patient</a>
                    <?php if ($selectedPatient): ?>
                        <a href="Dentist-patient.php?view=<?= $selectedPatient['PatientID'] ?>">
                            <?= $selectedPatient['Firstname'] . ' ' . $selectedPatient['Lastname'] ?>
                            <img src="icons/dentalassistant/exit.png" class="close">
                        </a>
                    <?php endif; ?>
                </div>
                <div class="profile_left">
                    <div id="info" style="text-align: left;">
                        <p id="fullname">Dr. <?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($lastname) ?></p>
                        <p id="status">Dentist</p>
                    </div>
                    <img id="profile_icon" src="<?= htmlspecialchars($profile_img) ?>" alt="Profile Icon">
                </div>
            </div>
            
            <!-- Main content area: display patient list if no patient is selected -->
            <?php if (!$selectedPatient): ?>
            <div id="content">
                <h1>Patients</h1>
                <div class="search_group">
                    <div class="search_box">
                        <div class="row">
                            <span class="material-symbols-outlined">search</span>
                            <input type="text" id="input-box" placeholder="Search patient name or patient ID" autocomplete="off">
                        </div>
                        <div class="result_box"></div>
                    </div>
                    <button id="search_btn">SEARCH</button>
                    <div class="filter_group"></div>
                </div>

                <table class="patient_table">
                    <thead>
                        <tr>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Sex</th>
                            <th>Age</th>
                            <th>Contact Details</th>
                            <th>Recent Procedure</th>
                            <th>Attending Physician</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($patient = mysqli_fetch_assoc($patientResult)): ?>
                        <tr>
                            <td><?= $patient['PatientID'] ?></td>
                            <td><?= $patient['Firstname'] . ' ' . $patient['Lastname'] ?></td>
                            <td class="cell_center_content"><?= $patient['Sex'] ?></td>
                            <td class="cell_center_content"><?= $patient['Age'] ?></td>
                            <td class="cell_center_content"><?= $patient['ContactDetails'] ?></td>
                            <td><?= $appointments[$patient['PatientID']]['AppointmentType'] ?? 'N/A' ?></td>
                            <td>
                                <?php
                                if (isset($appointments[$patient['PatientID']])) {
                                    echo $appointments[$patient['PatientID']]['DentistFirst'] . ' ' . $appointments[$patient['PatientID']]['DentistLast'];
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="view_btn" style="border: none; background: none; padding: 0;">
                                    <a href="Dentist-patient.php?view=<?= $patient['PatientID'] ?>">
                                        <img src="icons/specialist/view.png" alt="View" style="width: 20px; height: 20px;">
                                    </a>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
            
            <!-- Patient detail section when a patient is selected -->
            <?php if ($selectedPatient): ?>
            <div id="patient-section" class="section" style="display: block;">
                <!-- Patient details header -->
                <div class="header">
                    <div class="profile">
                        <img src="<?= $selectedPatient['img'] ?>" id="profile-pic_patientpage">
                        <div class="profile_info_container">
                            <div class="name">
                                <p>Patient ID: <?= $selectedPatient['PatientID'] ?></p>
                                <h2><?= $selectedPatient['Firstname'] . ' ' . $selectedPatient['Lastname'] ?></h2>
                            </div>
                            <div class="profile-info">
                                <div class="sex_container">
                                    <img src="icons/specialist/sex_icon.png" alt="Sex" class="details_icon">
                                    <div class="label">
                                        <p id="gray">Sex</p>
                                        <p><?= $selectedPatient['Sex'] ?></p>
                                    </div>
                                </div>
                                <div class="age_container">
                                    <img src="icons/specialist/age_icon.png" alt="Age" class="details_icon">
                                    <div class="label">
                                        <p id="gray">Age</p>
                                        <p><?= $selectedPatient['Age'] ?> y/o</p>
                                    </div>
                                </div>
                                <div class="birthday_container">
                                    <img src="icons/specialist/birthday_icon.png" alt="Birthday" class="details_icon">
                                    <div class="label">
                                        <p id="gray">Birthday</p>
                                        <p><?= date("F j, Y", strtotime($selectedPatient['Birthday'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Navigation -->
                <div id="tabs">
                    <div class="sub-navigation">
                        <a href="#" onclick="showTabContent('overview-content')">Overview</a>
                        <a href="#" onclick="showTabContent('personal-content')">Personal Information</a>
                        <a href="#" onclick="showTabContent('appointment-content')">Appointment History</a>
                        <a href="#" onclick="showTabContent('laboratory-content')">Laboratory Results</a>
                        <a href="#" onclick="showTabContent('medication-content')">Medications</a>
                    </div>
                </div>
                
                <!-- Tab Contents -->
                <div class="content">
                    <!-- Overview Tab -->
                    <div class="overview-content">
                        <div class="allcontent">
                            <div class="column1_container">
                                <h3>Chart overview</h3>
                                <div class="stats_container">
                                    <div class="chart_wrapper">
                                        <canvas id="appointmentPieChart"></canvas>
                                    </div>
                                    <div class="stats_details">
                                        <div class="stat_item">
                                            <span class="stat_number" style="color: #007bff;"><?= $totalCompletedAppointments ?></span>
                                            <div class="stat_label">Completed Appointments</div>
                                        </div>
                                        <div class="stat_item">
                                            <span class="stat_number" style="color: #c97a33;"><?= $totalPenaltyAppointments ?></span>
                                            <div class="stat_label">Penalty Appointments</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="medical_history_container">
                                    <h3>Prescription History</h3>
                                    <table class="medical_history_table">
                                        <thead>
                                            <tr>
                                                <th>Medication</th>
                                                <th>Dosage</th>
                                                <th>Instructions</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($prescriptionResult && mysqli_num_rows($prescriptionResult) > 0): ?>
                                                <?php while ($prescription = mysqli_fetch_assoc($prescriptionResult)): ?>
                                                    <!-- For the Overview table, you could display aggregated data.
                                                         Here we use the fields from the normalized query if needed. -->
                                                    <tr>
                                                        <td><?= htmlspecialchars($prescription['Medicine']) ?></td>
                                                        <td><?= htmlspecialchars($prescription['Dosage']) ?></td>
                                                        <td><?= htmlspecialchars($prescription['Instructions']) ?></td>
                                                        <td><?= htmlspecialchars($prescription['Notes'] ?? 'N/A') ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" style="text-align: center;">No prescriptions found</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="column2_container">
                                <div class="recentvisit_container">
                                    <h3>Recent Visit</h3>
                                    <?php if ($lastCompletedAppointment): ?>
                                        <div class="visit_container">
                                            <div class="column1">
                                                <p class="doctor_name">
                                                    Dr. <?= htmlspecialchars($lastCompletedAppointment['DentistFirst'] . ' ' . $lastCompletedAppointment['DentistLast']) ?>
                                                </p>
                                                <p class="specialization"><?= htmlspecialchars($lastCompletedAppointment['Specialization']) ?></p>
                                                <p class="p_styled"><?= htmlspecialchars($lastCompletedAppointment['AppointmentType']) ?></p>
                                            </div>
                                            <div class="column2">
                                                <p class="date_visit"> 
                                                    <img src="icons/dentalassistant/calendar_gray_icon.png">
                                                    <?= date("F j, Y", strtotime($lastCompletedAppointment['AppointmentDate'])) ?>
                                                </p>
                                                <p class="time_visit">
                                                    <img src="icons/dentalassistant/clock_icon.png">
                                                    <?= htmlspecialchars($lastCompletedAppointment['TimeStart']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <p style="text-align: center; color: gray;">No completed appointments found.</p>
                                    <?php endif; ?>
                                </div>

                                <!-- Files and Documents Section (e.g., Laboratory Results) -->
                                <div class="files_and_documents">
                                    <div class="file_header">
                                        <h3>Laboratory</h3>
                                        <button class="upload" onclick="openUploadModal()">
                                            <img src="icons/specialist/upload_icon.png"> UPLOAD
                                        </button>
                                    </div>
                                </div>

                                <!-- Upload Modal -->
                                <div id="uploadModal" class="modal">
                                    <div class="modal-content">
                                        <span class="close" onclick="closeUploadModal()">&times;</span>
                                        <h2>Upload Medical Record</h2>
                                        <form id="uploadForm" action="Assistant-patients.php" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="PatientID" value="<?= $selectedPatient['PatientID'] ?>">
                                            <label for="subject">Subject:</label>
                                            <input type="text" name="subject" id="subject" required>
                                            
                                            <label for="file">Choose File:</label>
                                            <input type="file" name="file" id="file" accept=".pdf,.docx" required>
                                            
                                            <button type="submit">Upload</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Personal Information Tab -->
                    <div class="personal-content" style="display: none;">
                        <h3>Personal Information</h3>
                        <div class="division">
                            <div class="column1_personal_info">
                                <div class="personal_container">
                                    <p class="h4">Personal Data</p>
                                    <div class="nameline">
                                        <div class="firstname_container">
                                            <p class="gray_styled">First Name</p>
                                            <p class="content_info"><?= $selectedPatient['Firstname'] ?></p>
                                        </div>
                                        <div class="middlename_container">
                                            <p class="gray_styled">Middle Name</p>
                                            <p class="content_info"><?= $selectedPatient['Middlename'] ?? '' ?></p>
                                        </div>
                                        <div class="lastname_container">
                                            <p class="gray_styled">Last Name</p>
                                            <p class="content_info"><?= $selectedPatient['Lastname'] ?></p>
                                        </div>
                                    </div>
                                    <div class="other_info_line">
                                        <div class="sex_title_container">
                                            <p class="gray_styled">Sex</p>
                                            <p class="content_info"><?= $selectedPatient['Sex'] ?></p>
                                        </div>
                                        <div class="age_title_container">
                                            <p class="gray_styled">Age</p>
                                            <p class="content_info"><?= $selectedPatient['Age'] ?> y/o</p>
                                        </div>
                                        <div class="birthday_title_container">
                                            <p class="gray_styled">Birthday</p>
                                            <p class="content_info"><?= date("F j, Y", strtotime($selectedPatient['Birthday'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <p class="h4">Address</p>
                                <div class="address_container">
                                    <div class="house_container">
                                        <p class="gray_styled">House Number/Street</p>
                                        <p class="content_info"><?= $selectedPatient['HouseNumberStreet'] ?></p>
                                    </div>
                                    <div class="house_brgy_container">
                                        <p class="gray_styled">Barangay</p>
                                        <p class="content_info"><?= $selectedPatient['Barangay'] ?></p>
                                    </div>
                                    <div class="house_city_container">
                                        <p class="gray_styled">City/Municipality</p>
                                        <p class="content_info"><?= $selectedPatient['CityMunicipality'] ?></p>
                                    </div>
                                </div>
                                <p class="h4">Contact Details</p>
                                <div class="contact_details_container">
                                    <div class="email_container">
                                        <p class="gray_styled">Email</p>
                                        <p class="content_info"><?= $selectedPatient['Email'] ?></p>
                                    </div>
                                    <div class="mobilenum_container">
                                        <p class="gray_styled">Mobile Number</p>
                                        <p class="content_info"><?= $selectedPatient['ContactDetails'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Appointment History Tab -->
                    <div class="appointment-content" style="display: none;">
                        <table class="appointment_history_table">
                            <thead>
                                <tr>
                                    <th>Appointment Status</th>
                                    <th>Appointment Date</th>
                                    <th>Payment Type</th>
                                    <th>Payment Status</th>
                                    <th>Time</th>
                                    <th>Requested Dentist</th>
                                    <th>Reason for Appointment</th>
                                    <th>Appointment Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($appointmentsHistoryResult && mysqli_num_rows($appointmentsHistoryResult) > 0): ?>
                                    <?php while ($appointment = mysqli_fetch_assoc($appointmentsHistoryResult)): ?>
                                        <tr>
                                            <td>
                                                <p class="appointment_status <?= ($appointment['AppointmentStatus'] == 'Completed') ? 'completed' : 'upcoming' ?>">
                                                    <?= htmlspecialchars($appointment['AppointmentStatus']) ?>
                                                </p>
                                            </td>
                                            <td><?= date("F j, Y", strtotime($appointment['AppointmentDate'])) ?></td>
                                            <td><p class="p_styled"><?= htmlspecialchars($appointment['PaymentType']) ?></p></td>
                                            <td>
                                                <p class="payment_status <?= ($appointment['PaymentStatus'] == 'Paid') ? 'paid' : 'pending' ?>">
                                                    <?= htmlspecialchars($appointment['PaymentStatus']) ?>
                                                </p>
                                            </td>
                                            <td><?= date("h:i A", strtotime($appointment['TimeStart'])) . " - " . date("h:i A", strtotime($appointment['TimeEnd'])) ?></td>
                                            <td>Dr. <?= htmlspecialchars($appointment['DentistFirst'] . ' ' . $appointment['DentistLast']) ?></td>
                                            <td><?= htmlspecialchars($appointment['Reason']) ?></td>
                                            <td><?= htmlspecialchars($appointment['AppointmentType']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; color: gray;">No appointments found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Laboratory Results Tab -->
                    <div class="laboratory-content" style="display: none;">
                        <div class="laboratory_container">
                            <?php if ($medicalRecordResult && mysqli_num_rows($medicalRecordResult) > 0): ?>
                                <?php while ($record = mysqli_fetch_assoc($medicalRecordResult)): ?>
                                    <div class="block_container">
                                        <div class="details_container">
                                            <div class="leftside">
                                                <img src="icons/patient/file_icon.png">
                                                <div class="filedeets">
                                                    <h2 class="big_file_name"><?= htmlspecialchars($record['subject']) ?></h2>
                                                    <div class="metadata">
                                                        <p class="file_date"><?= date("F d, Y", strtotime($record['dateSubmitted'])) ?></p>
                                                        <p>•</p>
                                                        <p class="file_time"><?= date("h:i A", strtotime($record['timeSubmitted'])) ?></p>
                                                        <p>•</p>
                                                        <p class="file_tag">Laboratory Result</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (file_exists($record['filename'])): ?>
                                                <a href="<?= htmlspecialchars($record['filename']) ?>" target="_blank">
                                                    <button class="view_button">VIEW</button>
                                                </a>
                                            <?php else: ?>
                                                <p style="color: red;">⚠️ Error: File not found!</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p style="text-align: center; color: gray;">No medical records found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Medications Tab (Normalized Prescription Details) -->
                    <?php
                        if ($selectedPatient) {
                            $patientID = $selectedPatient['PatientID'];
                            
                            // Ensure PrescriptionID is populated for all records
                            mysqli_query($connection, "UPDATE prescription SET PrescriptionID = CONCAT('PREID', LPAD(id, 4, '0')) WHERE PrescriptionID IS NULL OR PrescriptionID = ''");
                            
                            // New prescription query joining normalized tables
                            $prescriptionQuery = "
                                SELECT 
                                p.PrescriptionID, 
                                p.PrescriptionDate, 
                                p.Notes, 
                                p.created_at,
                                d.Firstname AS DoctorFirst, 
                                d.Middlename AS DoctorMiddle, 
                                d.Lastname AS DoctorLast,
                                pm.Medicine, 
                                pm.Dosage, 
                                pm.Instructions, 
                                pm.RefillStatus
                                FROM prescription p
                                JOIN dentist d ON p.DentistID = d.DentistID
                                JOIN prescription_medicines pm ON p.PrescriptionID = pm.PrescriptionID
                                WHERE p.PatientID = '$patientID'
                                ORDER BY p.PrescriptionDate DESC
                            ";
                            
                            $prescriptionResult = mysqli_query($connection, $prescriptionQuery);
                            if (!$prescriptionResult) {
                                die("Query failed: " . mysqli_error($connection));
                            }
                        }
                        ?>

                        <div class="medication-content">
                            <?php if ($prescriptionResult && mysqli_num_rows($prescriptionResult) > 0): ?>
                                <?php while ($prescription = mysqli_fetch_assoc($prescriptionResult)): ?>
                                    <div class="medication_container">
                                        <div class="block_container">
                                            <div class="details_container">
                                                <div class="leftside">
                                                    <img src="icons/dentist/records_icon.png" alt="Record Icon">
                                                    <div class="filedeets">
                                                        <h2 class="big_file_name">
                                                            Prescription from Dr. <?php echo htmlspecialchars($prescription['DoctorFirst'] . ' ' . $prescription['DoctorLast']); ?>
                                                        </h2>
                                                        <div class="metadata">
                                                            <p class="file_date"><?php echo date("F j, Y", strtotime($prescription['PrescriptionDate'])); ?></p>
                                                            <p>•</p>
                                                            <p class="file_time"><?php echo date("h:i A", strtotime($prescription['created_at'])); ?></p>
                                                            <p>•</p>
                                                            <p class="file_tag">Prescription</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="prescription_details">
                                                    <p><strong>Medicine:</strong> <?php echo htmlspecialchars($prescription['Medicine']); ?></p>
                                                    <p><strong>Dosage:</strong> <?php echo htmlspecialchars($prescription['Dosage']); ?></p>
                                                    <p><strong>Instructions:</strong> <?php echo htmlspecialchars($prescription['Instructions']); ?></p>
                                                    <p><strong>Refill Status:</strong> <?php echo htmlspecialchars($prescription['RefillStatus']); ?></p>
                                                </div> -->
                                                <a href="prescription_generate_pdf.php?prescription_id=<?php echo $prescription['PrescriptionID']; ?>" target="_blank">
                                                    <button class="view_button">VIEW</button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No prescription records found for this patient.</p>
                            <?php endif; ?>
                        </div>

                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Link to JS -->
    <script src="js/dental_assistant.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Tab switching function exposed globally
        function showTabContent(contentId) {
            const contents = document.querySelectorAll('.content > div');
            contents.forEach(content => content.style.display = 'none');
            const selectedContent = document.querySelector(`.${contentId}`);
            if (selectedContent) {
                selectedContent.style.display = 'block';
            }
            const navLinks = document.querySelectorAll('#tabs .sub-navigation a');
            navLinks.forEach(link => link.classList.remove('active'));
            const clickedLink = document.querySelector(`#tabs .sub-navigation a[onclick="showTabContent('${contentId}')"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
        }
        window.showTabContent = showTabContent;
        // Default tab
        document.addEventListener("DOMContentLoaded", function () {
            showTabContent('overview-content');
        });

        // Section toggling for patient list vs. patient detail view
        document.addEventListener("DOMContentLoaded", function () {
            function showRightPanelSection(sectionId) {
                const content = document.getElementById("content");
                const patientSection = document.getElementById("patient-section");
                if (sectionId === "patient-section") {
                    if (content) { content.style.display = "none"; }
                    if (patientSection) { patientSection.style.display = "block"; }
                } else {
                    if (content) { content.style.display = "block"; }
                    if (patientSection) { patientSection.style.display = "none"; }
                }
            }
            document.querySelectorAll(".close").forEach(closeBtn => {
                closeBtn.addEventListener("click", function (event) {
                    event.stopPropagation();
                    showRightPanelSection("content");
                });
            });
            document.querySelectorAll(".view_btn").forEach(viewBtn => {
                viewBtn.addEventListener("click", function () {
                    showRightPanelSection("patient-section");
                });
            });
            if (localStorage.getItem("patientSectionVisible") !== "true") {
                const patientLink = document.querySelector(".sub-navigation a[href='#'][onclick*='patient-section']");
                if (patientLink) patientLink.style.display = "none";
                showRightPanelSection("content");
            }
            window.showRightPanelSection = showRightPanelSection;
        });

        // Chart rendering
        document.addEventListener('DOMContentLoaded', function () {
            var canvas = document.getElementById('appointmentPieChart');
            if (canvas) {
                var ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Completed Appointments", "Penalty Appointments"],
                        datasets: [{
                            data: [<?= $totalCompletedAppointments ?? 0 ?>, <?= $totalPenaltyAppointments ?? 0 ?>],
                            backgroundColor: ["#007bff", "#c97a33"],
                            hoverBackgroundColor: ["#0056b3", "#a15e28"]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
        
        // Modal functions
        function openUploadModal() {
            document.getElementById("uploadModal").style.display = "block";
        }
        function closeUploadModal() {
            document.getElementById("uploadModal").style.display = "none";
        }
        window.onclick = function(event) {
            var modal = document.getElementById("uploadModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        document.getElementById('input-box').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.patient_table tbody tr');
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let text = '';
                cells.forEach(cell => text += cell.textContent.toLowerCase());
                row.style.display = text.indexOf(filter) > -1 ? '' : 'none';
            });
        });

    </script>
</body>
</html>
