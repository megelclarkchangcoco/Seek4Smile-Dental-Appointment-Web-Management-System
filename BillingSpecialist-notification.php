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

    // ✅ Fetch Notifications for Paid Appointments
    $query = "SELECT ab.BillingID, ab.PaymentStatus, ab.PaymentType, ab.CreatedAt 
              FROM appointmentbilling ab
              WHERE ab.PaymentStatus = 'paid'
              ORDER BY ab.CreatedAt DESC";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database Error: " . mysqli_error($connection));
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
    <title>Notification</title>
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
                <div id="info_specialist" style="text-align: left;">
                    <p id="fullname_specialist"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status_specialist">Medical Billing Specialist</p>
                </div>
                <img id="profile_icon_specialist" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Profile Icon">
            </div>

            <div class="notification-p">
                <p>Notification</p>
            </div>
            
            <div class="notification-wrapper">

                <?php
                    if(mysqli_num_rows($result) > 0 ){
                        while($row = mysqli_fetch_assoc($result)){
                            $billingID = htmlspecialchars($row['BillingID']);
                            $paymentType = htmlspecialchars($row['PaymentType']);
                            $paymentDate = date("M d, Y", strtotime($row['CreatedAt']));
                ?>          
                    <div class="notification-container">
                        <div class="notification-image">
                            <img src="icons/patient/check_icon.png" class="sign_icon">
                        </div>
                        <div class="alert">
                            <h2>Appointment Paid</h2>
                            <p>Patient paid their appointment via <?php echo ucfirst($paymentType); ?>

                            <p class="date"><?php echo $paymentDate; ?></p>
                            <p class="billingID" style="margin-bottom: 20px;">ID: <?php echo $billingID; ?></p>
                        </div>
                    </div>
                <?php
                        }
                    } else {
                        echo "<p style='text-align:center; color:gray;'>No new paid appointments</p>";
                    }
                ?>  
            </div>

        </div>
    </div>

    <script>
        // Optional: Auto-refresh notifications every 30 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>

<?php 
    mysqli_close($connection);
?>