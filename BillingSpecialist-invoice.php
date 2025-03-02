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

    // Capture the search input
    $searchQuery = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($connection, $_GET['search']);
        $searchQuery = " AND (
            pb.BillingID LIKE '%$search%' 
            OR p.Firstname LIKE '%$search%' 
            OR p.Lastname LIKE '%$search%' 
            OR a.AppointmentType LIKE '%$search%' 
            OR pb.PaymentStatus LIKE '%$search%'
        )";
    }

    // Fetch only unpaid invoices
    $query = "
        SELECT 
            pb.BillingID, 
            pb.PatientID, 
            p.Firstname, 
            p.Lastname, 
            a.AppointmentID, 
            a.AppointmentType AS TreatmentName, 
            CASE 
                WHEN a.AppointmentType = 'Procedure' THEN a.AppointmentProcedure
                WHEN a.AppointmentType = 'Treatment' THEN a.AppointmentTreatment
                WHEN a.AppointmentType = 'Laboratory' THEN a.AppointmentLaboratory
                ELSE 'N/A'
            END AS TreatmentType,
            a.AppointmentStatus,
            pb.PaymentType, 
            pb.PaymentStatus, 
            pb.TotalFee, 
            pb.CreatedAt
        FROM appointmentbilling pb
        JOIN appointment a ON pb.AppointmentID = a.AppointmentID
        JOIN patient p ON pb.PatientID = p.PatientID
        WHERE pb.PaymentStatus = 'unpaid' $searchQuery
        ORDER BY pb.CreatedAt ASC
    ";

    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/specialist/toothlogos.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/specialiststyle.css">
    <title>Invoice</title>
</head>
<body> 
    <div id="wrapper">
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
                <div id="info_specialist" style="text-align: left;">
                    <p id="fullname_specialist"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status_specialist">Medical Billing Specialist</p>
                </div>
                <img id="profile_icon_specialist" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="main-content">
                <h1>Invoice Information</h1>
                <div class="search_group">
                    <form method="GET" action="">
                        <div class="search_box">
                            <div class="row">
                            <input type="text" name="search" id="input-box" placeholder="Search invoices" 
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" autocomplete="off">  
                            </div>
                        </div>
                    </form>
                        <button type="submit" id="search_btn">SEARCH</button>
                </div>

                <table class="appointment-requests">
                    <thead>
                        <tr>
                            <th>Billing ID</th>
                            <th>Patient ID</th>
                            <th>Patient Name</th>
                            <th>Treatment Name</th>
                            <th>Treatment Type</th>
                            <th>Treatment Status</th>
                            <th>Payment Option</th>
                            <th>Payment Status</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr>
                                    <td><?= $row['BillingID'] ?></td>
                                    <td><?= $row['PatientID'] ?></td>
                                    <td><?= $row['Firstname'] . ' ' . $row['Lastname'] ?></td>
                                    <td><?= ucfirst($row['TreatmentName']) ?></td>
                                    <td><?= $row['TreatmentType'] ?: 'N/A' ?></td>
                                    <td><?= ucfirst($row['AppointmentStatus']) ?></td>
                                    <td><?= ucfirst($row['PaymentType']) ?></td>
                                    <td class="status unpaid">Unpaid</td>
                                    <td>₱<?= number_format($row['TotalFee'], 2) ?></td>
                                    <td><?= date("M d, Y", strtotime($row['CreatedAt'])) ?></td>
                                    <td class="cell_center_content">
                                        <a href="generate_invoice_billing.php?billingID=<?= $row['BillingID'] ?>" target="_blank">
                                            <button class="view_btn">
                                                <span class="material-symbols-outlined">
                                                    <img src="icons/specialist/invoice.png" alt="Generate Invoice">
                                                </span>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>  
                        <?php else : ?>
                            <tr>
                                <td colspan="11" style="text-align: center;">No unpaid invoices found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    

    
</body>
</html>
