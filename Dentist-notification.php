<?php
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['DentistID'])) {
        header('Location: index.php');
        exit();
    }

    $dentistID = $_SESSION['DentistID'];  // Get the currently logged-in DentistID
    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Fetch appointments only for the logged-in dentist
    $query = "SELECT AppointmentID, PatientID, AppointmentDate, appointmentStatus 
            FROM appointment 
            WHERE DentistID = ? 
            ORDER BY CreatedAt DESC"; 

    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $dentistID);  // "s" for string since DentistID is alphanumeric (DENID001, etc.)
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <title>Notifications</title>
</head>
<body> 

    <div id="wrapper">
        <div id="left_panel">
                <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo"> <!-- Add your logo image path -->
            
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
