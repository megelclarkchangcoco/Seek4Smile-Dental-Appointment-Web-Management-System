<?php  
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['DentistID'])) {
        header('Location: logout.php');
        exit();
    }

    // Use null coalescing to avoid undefined warnings.
    $assistantID = $_SESSION['DentistID'] ?? '';
    $firstname   = $_SESSION['Firstname'] ?? '';
    $lastname    = $_SESSION['Lastname'] ?? '';
    $email = $assistantData['Email'] ?? 'No Email Provided';
    $phone = $assistantData['ContactDetails'] ?? 'No Contact Provided';
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Fetch assistant working hours from the dentistassistant_working_hour table.
    $sql = "SELECT * FROM dentist_working_hour WHERE DentistID = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $connection->error);
    }
    $stmt->bind_param("s", $assistantID);
    $stmt->execute();
    $result = $stmt->get_result();
    $workingHours = $result->fetch_assoc();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <title>Dental Assistant - Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
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
                    <p id="fullname"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status">Dental Assistant</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <!-- this div where the main content located-->
            <div id="content"> 
                <h1>Profile</h1>
                <div class="profile_container">
                    <div class="profile_info">
                        <div class="img_profile">
                            <img src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" id="profile_pic">
                        </div>
                        <div class="name">
                            <h2><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></h2>
                            <p>Dental Assistant</p>
                            <p>ID:<?php echo htmlspecialchars($assistantID)?></p>
                        </div>
                    </div>
                    <div class="contactdetails_container">
                    <h3>Contact Details</h3>
                        <div class="contactdetails">
                            <div class="email">
                                <img src="icons/dentalassistant/email_gray_icon.png" alt="Email">
                                <div class="label">
                                    <p id="gray">Email</p>
                                    <p><?php echo htmlspecialchars($email); ?></p>
                                </div>
                            </div>
                            <div class="phonenum">
                                <img src="icons/dentalassistant/phone_gray_icon.png" alt="Phone">
                                <div class="label">
                                    <p id="gray">Mobile Phone Number</p>
                                    <p><?php echo htmlspecialchars($phone); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                        
                     <!-- Working hours -->
                <div class="working-hours">
                    <h3>Working Hours</h3>
                    <div class="schedule-grid">
                        <div class="schedule-item <?php echo (date('l') === 'Monday' ? 'today' : ''); ?>">
                            <h4>MONDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Monday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Tuesday' ? 'today' : ''); ?>">
                            <h4>TUESDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Tuesday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Wednesday' ? 'today' : ''); ?>">
                            <h4>WEDNESDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Wednesday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Thursday' ? 'today' : ''); ?>">
                            <h4>THURSDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Thursday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Friday' ? 'today' : ''); ?>">
                            <h4>FRIDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Friday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Saturday' ? 'today' : ''); ?>">
                            <h4>SATURDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Saturday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                        <div class="schedule-item <?php echo (date('l') === 'Sunday' ? 'today' : ''); ?>">
                            <h4>SATURDAY</h4>
                            <div class="time">
                                <i class="clock-icon">🕐</i>
                                <span><?php echo htmlspecialchars($workingHours['Sunday'] ?? 'Not Available'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                </div>



            </div>

        </div>
    </div>
</body>
</html>