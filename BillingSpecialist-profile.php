<?php 
    include 'php/connection.php';
    session_start();

    // Ensure user is logged in
    if (!isset($_SESSION['SpecialistID'])) {
        header("Location: login.php");
        exit;
    }

    // ✅ Fix: Check if session keys exist before using them
    $billingID = $_SESSION['SpecialistID'];
    $firstname = $_SESSION['Firstname'] ?? "Unknown"; // Default if not set
    $lastname = $_SESSION['Lastname'] ?? "Unknown";
    $email = $_SESSION['Email'] ?? "Not Available";
    $contact = $_SESSION['ContactDetails'] ?? "Not Available";
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Fetch Working Hours
    $query = "SELECT * FROM billingspecialist_working_hour WHERE SpecialistID = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $billingID);
    $stmt->execute();
    $result = $stmt->get_result();
    $workingHours = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/specialist/toothlogos.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/specialiststyle.css">
    <title>Billing Specialist Profile</title>
</head>
<body> 
    <div id="wrapper">
        <div id="left_panel">
            <img id="logo" src="icons/specialist/logo_seek4smiles.png" alt="Logo">
        
            <label>
                <a href="BillingSpecialist-Homepage.php">
                    <img src="icons/specialist/home_icon.png"> Homepage
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-notification.php">
                    <img src="icons/specialist/notification_icon.png"> Notifications
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-PatientInformation.php">
                    <img src="icons/specialist/medical_record_icon.png"> Patient Information
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-billing.php">
                    <img src="icons/specialist/prescription.png"> Billing
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-invoice.php">
                    <img src="icons/specialist/invoice.png"> Invoices
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-profile.php">
                    <img src="icons/specialist/personnel_icon.png"> Profile
                </a>
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/specialist/signout_icon.png"> Sign Out
                </a>
            </label>
        </div>

        <div id="right_panel">
            <div class="profile-container">
                <div class="profile-header">
                    <img src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Photo">
                    <div>
                        <h1><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></h1>
                        <p>Medical Billing Specialist</p>
                        <p>ID: <?php echo htmlspecialchars($billingID)?></p>
                    </div>
                </div>

                <div class="contact-details">
                    <div class="contact-item">
                        <img src="icons/specialist/mail.png" alt="Email">
                        <span>Email:</span> <?php echo htmlspecialchars($email)?>
                    </div>
                    <div class="contact-item">
                        <img src="icons/specialist/telephone.png" alt="Phone">
                        <span>Phone:</span> <?php echo htmlspecialchars($contact)?>
                    </div>
                </div>

                <h3>Working Hours</h3>
                <div class="working-hours">
                <?php
                    // Days of the week
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                    foreach ($days as $day) {
                        if (!empty($workingHours[$day]) && strtolower($workingHours[$day]) !== 'closed') {
                            echo "
                            <div class='day-card'>
                                <img src='icons/specialist/clock.png' alt='Clock'>
                                <p><b>$day</b></p>
                                <p>{$workingHours[$day]}</p>
                            </div>";
                        }
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
