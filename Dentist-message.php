<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['DentistID'])) {
        header("Location: login.php");
        exit;
    }

    $dentist_id = $_SESSION['DentistID'];
    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="css/patientstyle.css">
    <link rel="stylesheet" href="css/message.css">
    <title>Appointment</title>
</head>
<body> 
    <!-- main div -->   
    <div id="wrapper">

        <!-- this the left panel located-->
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

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname">Dr. <?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Dentist</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="message-p">
                <p>Messages</p>
            </div>
            <div class="message-search-wrapper">
                <div class="message-search-field">
                    <img src="icons/patient/search_icon.png" alt="Search Icon" class="message-search-icon" width="20" height="20">
                    <input type="text" class="message-search-input" placeholder="Search by doctor name or specialization">
                </div>
                <button class="message-search-button">Search</button>        
            </div>

            
        <?php
        // Fetch patients who are online or have had the last chat with the dentist
        $sql = "SELECT p.*, 
                (SELECT msg FROM messages 
                WHERE (outgoing_msg_id = p.PatientID AND incoming_msg_id = ?)
                OR (outgoing_msg_id = ? AND incoming_msg_id = p.PatientID) 
                ORDER BY msg_id DESC LIMIT 1) AS last_message,
                (SELECT created_at FROM messages 
                WHERE (outgoing_msg_id = p.PatientID AND incoming_msg_id = ?)
                OR (outgoing_msg_id = ? AND incoming_msg_id = p.PatientID) 
                ORDER BY msg_id DESC LIMIT 1) AS last_message_time
                FROM patient p
                WHERE p.status = 'Online' 
                OR EXISTS (SELECT 1 FROM messages 
                        WHERE (outgoing_msg_id = p.PatientID AND incoming_msg_id = ?)
                        OR (outgoing_msg_id = ? AND incoming_msg_id = p.PatientID))";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssssss", $dentist_id, $dentist_id, $dentist_id, $dentist_id, $dentist_id, $dentist_id);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
            <div class="message-wrapper">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($patient = $result->fetch_assoc()): ?>
                        <a href="Dentist-chat.php?patient_id=<?= htmlspecialchars($patient['PatientID']) ?>">
                            <div class="message-contaienr">
                                <img src="<?= !empty($patient['img']) ? htmlspecialchars($patient['img']) : 'img/user_default.png' ?>" 
                                    class="patient-image" 
                                    alt="<?= htmlspecialchars($patient['Firstname']) ?>">
                                <div class="doctor-details">
                                    <h2><?= htmlspecialchars($patient['Firstname'] . ' ' . $patient['Lastname']) ?></h2>
                                    <p><?= !empty($patient['last_message']) 
                                            ? htmlspecialchars($patient['last_message']) 
                                            : 'No messages yet' ?></p>
                                </div>
                                <div class="status-dot <?= $patient['status'] === 'Online' ? 'online' : '' ?>"></div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-users">No online patients available</div>
                <?php endif; ?>
            </div>
    </div>
    <script src="js/chat-list.js"></script>

</body>
</html>
