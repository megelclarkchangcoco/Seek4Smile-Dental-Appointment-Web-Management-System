<?php
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['SpecialistID'])) {
        header("Location: login.php");
        exit;
    }

    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Check if a patient is being viewed
    $selectedPatient = isset($_GET['view']) ? $_GET['view'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/specialist/toothlogos.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/specialiststyle.css">
    <title>Patient Information</title>
</head>
<body> 
    <div id="wrapper">
        <!-- Left panel code remains the same -->
        <div id="left_panel">
            <img id="logo" src="icons/specialist/logo_seek4smiles.png" alt="Logo">
        
            <label>
                <a href="BillingSpecialist-Homepage.php">
                    <img src="icons/specialist/home_icon.png" alt="Home"> Homepage
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-notification.php">
                    <img src="icons/specialist/notification_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-PatientInformation.php">
                    <img src="icons/specialist/medical_record_icon.png" alt="Patient Information"> Patient Information
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-billing.php">
                    <img src="icons/specialist/prescription.png" alt="Billings"> Billing
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-invoice.php">
                    <img src="icons/specialist/invoice.png" alt="Invoice"> Invoices
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-profile.php">
                    <img src="icons/specialist/personnel_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/specialist/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <div id="right_panel">
            <div id="header_patient">
                <div class="sub-navigation">
                <a href="BillingSpecialist-PatientInformation.php">Patient</a>
                    <?php if ($selectedPatient): ?>
                        <a href="BillingSpecialist-PatientInformation.php">
                            <?php 
                                // Fetch selected patient name
                                $query = "SELECT Firstname, Lastname FROM patient WHERE PatientID = '$selectedPatient'";
                                $result = mysqli_query($connection, $query);
                                $patientData = mysqli_fetch_assoc($result);
                                $patientName = $patientData ? $patientData['Firstname'] . ' ' . $patientData['Lastname'] : "Unknown Patient";
                                echo $patientName; 
                            ?>
                            <img src="icons/specialist/exit.png" alt="Close">
                        </a>
                    <?php endif; ?>
                </div>
                <div id="info_specialist" style="text-align: left;">
                    <p id="fullname_specialist"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status_specialist">Medical Billing Specialist</p>
                </div>
                <img id="profile_icon_specialist" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>
            
            <?php if (!$selectedPatient): ?>
                <div class="main-content">
                    <h1>Patient Information</h1>
                    <div class="search_group">
                        <form method="GET" action="">
                            <div class="search_box">
                                <input type="text" name="search" id="input-box" placeholder="Search Patients"
                                    value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" autocomplete="off">
                            </div>
                        </form>
                        <button type="submit" id="search_btn">SEARCH</button>
                    </div>

                    



                    
                    <table class="appointment-requests">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Contact Number</th>
                                <th>Sex</th>
                                <th>Age</th>
                                <th>Actions</th>
                            </tr>   
                        </thead>
                        <tbody>
                            <?php
                                // Get search input
                                $search = isset($_GET['search']) ? mysqli_real_escape_string($connection, $_GET['search']) : '';

                                // Base SQL query
                                $sql = "SELECT PatientID, Firstname, Lastname, ContactDetails, Sex, Age FROM patient";

                                // If a search query is present, filter results
                                if (!empty($search)) {
                                    $sql .= " WHERE 
                                        PatientID LIKE '%$search%' OR 
                                        Firstname LIKE '%$search%' OR 
                                        Lastname LIKE '%$search%' OR 
                                        ContactDetails LIKE '%$search%'";
                                }

                                // Execute the query
                                $result = mysqli_query($connection, $sql);

                                // Check if results exist
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                            <td>{$row['PatientID']}</td>
                                            <td>{$row['Firstname']} {$row['Lastname']}</td>
                                            <td>{$row['ContactDetails']}</td>
                                            <td>{$row['Sex']}</td>
                                            <td>{$row['Age']}</td>
                                            <td style='text-align: center; vertical-align: middle;'>
                                                <button class='view_btn' style='display: inline-block;'>
                                                    <a href='BillingSpecialist-PatientInformation.php?view={$row['PatientID']}'>
                                                        <img src='icons/specialist/view.png' alt='View'>
                                                    </a>
                                                </button>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No patients found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
            <!-- Patient Section where the patient info appears -->
            <div id="patient-section">
                 <?php
                    // Fetch patient details
                    $query = "SELECT * FROM patient WHERE PatientID = '$selectedPatient'";
                    $result = mysqli_query($connection, $query);
                    $patient = mysqli_fetch_assoc($result);
                ?>
                <div class="header_patient">
                    <div class="profile_patient">
                            <img src="<?= $patient['img'] ?: 'img/user_default.png' ?>" alt="Profile Picture" id="profile-pic_patient">
                            <div class="profile-info">
                                <h2><?= $patient['Firstname'] . ' ' . $patient['Lastname'] ?></h2>
                                <p><strong>Sex:</strong> <?= $patient['Sex'] ?></p>
                                <p><strong>Age:</strong> <?= $patient['Age'] ?> y/o</p>
                                <p><strong>Birthday:</strong> <?= $patient['Birthday'] ?: "Unknown" ?></p>
                            </div>
                    </div>
                </div>

                <div id="tabs">
                    <div class="sub-navigation">
                        <a href="#" onclick="showTabContent('overview-content')" class="active">Overview</a>
                    </div>
                </div>

                <!-- Patient Information Content -->
                <div class="content">
                    <div class="overview-content">
                        <div class="billing-section">
                            <h2>Billing History</h2>
                            <table class="billing-table">
                                <thead>
                                    <tr>
                                        <th>Type Treatment</th>
                                        <th>Treatment Status</th>
                                        <th>Payment Type</th>
                                        <th>Payment Status</th>
                                        <th>Cost</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Fetch appointment and billing details for the selected patient
                                        $billingQuery = "
                                            SELECT 
                                                ab.BillingID,
                                                a.AppointmentID,
                                                COALESCE(a.AppointmentTreatment, a.AppointmentProcedure, a.AppointmentLaboratory, 'N/A') AS TreatmentName,
                                                a.AppointmentType AS Type,
                                                a.AppointmentStatus AS TreatmentStatus,
                                                ab.PaymentType,
                                                ab.PaymentStatus,
                                                ab.TotalFee AS Cost,
                                                ab.CreatedAt AS Date
                                            FROM appointmentbilling ab
                                            JOIN appointment a ON ab.AppointmentID = a.AppointmentID
                                            WHERE ab.PatientID = '$selectedPatient'
                                            ORDER BY ab.CreatedAt DESC";
                                        
                                        $billingResult = mysqli_query($connection, $billingQuery);

                                        if (mysqli_num_rows($billingResult) > 0) {
                                            while ($billing = mysqli_fetch_assoc($billingResult)) {
                                                echo "<tr>
                                                    <td>{$billing['Type']}</td>
                                                    <td class='status {$billing['TreatmentStatus']}'>{$billing['TreatmentStatus']}</td>
                                                    <td>{$billing['PaymentType']}</td>
                                                    <td class='payment {$billing['PaymentStatus']}'>{$billing['PaymentStatus']}</td>
                                                    <td>₱".number_format($billing['Cost'], 2)."</td>
                                                    <td>{$billing['Date']}</td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No billing records found.</td></tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>


                        <!-- Outstanding Balance Box -->
                        <div class="outstanding-balance">
                            <h3>Outstanding Balance</h3>
                            <p class="balance-amount">
                                PHP <span>
                                <?php
                                    // Fetch the total outstanding balance (unpaid transactions) for the selected patient
                                    $outstandingQuery = "
                                        SELECT SUM(TotalFee) AS OutstandingBalance
                                        FROM appointmentbilling
                                        WHERE PatientID = '$selectedPatient' AND PaymentStatus = 'unpaid'";

                                    $outstandingResult = mysqli_query($connection, $outstandingQuery);
                                    $outstandingData = mysqli_fetch_assoc($outstandingResult);
                                    echo number_format($outstandingData['OutstandingBalance'] ?? 0, 2);
                                ?>
                                </span>
                            </p>
                        </div>


                        <div id="personal-info">
                            <div class="personal-info-header">
                                <h2>Personal Information</h2>
                            </div>
                            <div class="form-section">
                                <div class="form-column">
                                    <h3>Personal Data</h3>
                                    <label>First Name</label>
                                    <input type="text" value="<?= $patient['Firstname']?>" readonly>
                                    
                                    <label>Middle Name</label>
                                    <input type="text" value="<?= $patient['Middlename']?>" readonly>
                
                                    <label>Last Name</label>
                                    <input type="text" value="<?= $patient['Lastname']?>" readonly>
                
                                    <label>Sex</label>
                                    <input type="text" value="<?= $patient['Sex']?>" readonly>
                
                                    <label>Age</label>
                                    <input type="text" value="<?= $patient['Age']?> y/o" readonly>
                
                                    <label>Birthday</label>
                                    <input type="text" value="<?= $patient['Birthday']?>" readonly>
                                </div>
                
                                <div class="form-column">
                                    <h3>Address</h3>
                                    <label>House Number/Street</label>
                                    <input type="text" value="<?= $patient['HouseNumberStreet']?>" readonly>
                
                                    <label>Barangay</label>
                                    <input type="text" value="<?= $patient['Barangay']?>" readonly>
                
                                    <label>City/Municipality</label>
                                    <input type="text" value="<?= $patient['CityMunicipality']?>" readonly>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    </div>


                </div>
            </div>

                
        </div>
    </div>

</body>
</html>
<?php mysqli_close($connection); ?>


