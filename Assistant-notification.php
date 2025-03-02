<?php
include 'php/connection.php';
session_start();

if (!isset($_SESSION['AssistantID'])) {
    header('Location: index.php');
    exit();
}

$firstname = $_SESSION['Firstname'];
$lastname = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// Fetch appointments from the database
$query = "SELECT AppointmentID, PatientID, AppointmentDate, appointmentStatus FROM appointment ORDER BY CreatedAt DESC LIMIT 10";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <title>Dental Assistant - Notifications</title>
</head>
<body> 

    <div id="wrapper">
        <div id="left_panel">
            <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo"> 
            <label>
                <a href="Assistant-homepage.php">
                    <img src="icons/dentalassistant/dashboard_icon.png" alt="Dashboard"> Dashboard
                </a>
            </label>
            <label>
                <a href="Assistant-notification.php">
                    <img src="icons/dentalassistant/notif_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Assistant-appointment.php">
                    <img src="icons/dentalassistant/calendar_icon.png" alt="Appointments"> Appointments
                </a>
            </label>
            <label>
                <a href="Assistant-patients.php">
                    <img src="icons/dentalassistant/patient_icon.png" alt="Patients"> Patients
                </a>
            </label>
            <label>
                <a href="Assistant-inventory.php">
                    <img src="icons/dentalassistant/inventory_icon.png" alt="Inventory"> Inventory
                </a>
            </label>
            <label>
                <a href="Assistant-profile.php">
                    <img src="icons/dentalassistant/profile_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="Logout.php">
                    <img src="icons/dentalassistant/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <div id="right_panel">
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($lastname) ?></p>
                    <p id="status">Dental Assistant</p>
                </div>
                <img id="profile_icon" src="<?= htmlspecialchars($profile_img) ?>" alt="Profile Icon">
            </div>

            <div id="content"> 
                <h1>Notifications</h1>
            </div>
            
            <div class="notification-wrapper">
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $patientID = htmlspecialchars($row['PatientID']);
                        $appointmentDate = date("F d", strtotime($row['AppointmentDate']));
                        $status = isset($row['appointmentStatus']) ? $row['appointmentStatus'] : '';  // Prevent warning

                        if ($status === 'Approved') {
                            echo '
                            <div class="notification-container">
                                <div class="notification-image">
                                    <img src="icons/patient/check_icon.png" class="sign_icon">
                                </div>
                                <div class="alert">
                                    <h2>New Appointment Booked</h2>
                                    <p>An appointment has been successfully scheduled for patient ' . $patientID . '.</p>
                                    <p class="date">' . $appointmentDate . '</p>
                                </div>
                            </div>';
                        } elseif ($status === 'Canceled') {
                            echo '
                            <div class="notification-container">
                                <div class="notification-image">
                                    <img src="icons/patient/warning_icon.png" class="sign_icon">
                                </div>
                                <div class="alert">
                                    <h2>Appointment Canceled</h2>
                                    <p>The appointment for patient ' . $patientID . ' has been canceled.</p>
                                    <p class="date">' . $appointmentDate . '</p>
                                </div>
                            </div>';
                        } else {
                            echo '
                            <div class="notification-container">
                                <div class="notification-image">
                                    <img src="icons/patient/check_icon.png" class="sign_icon">
                                </div>
                                <div class="alert">
                                    <h2>New Appointment Book</h2>
                                    <p>New appointment scheduled for patient ' . $patientID . '.</p>
                                    <p class="date">' . $appointmentDate . '</p>
                                </div>
                            </div>';
                        }
                    }
                } else {
                    echo '<p>No new notifications.</p>';
                }
                ?>
            </div>

        </div>
    </div>
</body>
</html>
