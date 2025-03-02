<?php 
    // Include the database connection file
    include 'php/connection.php';

    // Start the session to access session variables
    session_start();

    // Check if the user is logged in by verifying if 'PatientID' is set in the session 
    if (!isset($_SESSION['PatientID'])) {
        // If the user is not logged in, redirect to the login page
        header("Location: login.php");
        exit;
    }

    // Retrieve session variables for the logged-in patient
    $patientID = $_SESSION['PatientID'];
    $firstname = $_SESSION['Firstname']; // Patient's first name from the session
    $lastname = $_SESSION['Lastname'];   // Patient's last name from the session
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png'; // Patient's profile image, default if not set
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="css/patientstyle.css">
    <title>Medical Records</title>
</head>
<body> 
    <!-- main div -->   
    <div id="wrapper">

        <!-- this the left panel located-->
        <div id="left_panel">
            <img id="logo" src="icons/patient/logo_seek4smiles.png" alt="Logo"> <!-- Add your logo image path -->
        
            <label>
                <a href="Patient-Homepage.php">
                    <img src="icons/patient/home_icon.png" alt="Home"> Homepage
                </a>
            </label>
            <label>
                <a href="Patient-notification.php">
                    <img src="icons/patient/notification_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Patient-record.php">
                    <img src="icons/patient/medical_record_icon.png" alt="Medical Records"> Medical Records
                </a>
            </label>
            <label>
                <a href="Patient-prescription.php">
                    <img src="icons/patient/prescription.png" alt="Medical Records"> Prescription Records
                </a>
            </label>
            <label>
                <a href="Patient-appointment.php">
                    <img src="icons/patient/calendar_icon.png" alt="Appointments"> Appointments
                </a>
            </label>
            <label>
                <a href="Patient-message.php">
                    <img src="icons/patient/message_icon.png" alt="Messages"> Messages
                </a>
            </label>
            <label>
                <a href="Patient-billing.php">
                    <img src="icons/patient/billing_icons.png" alt="Billing"> Billing
                </a>
            </label>
            <label>
                <a href="Patient-profile.php">
                    <img src="icons/patient/profile_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>
        
        

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <!-- <div class="header-record">
                <div class="info-file">
                    <a class="info-a"href="Patient-record.html"><img src="icons/patient/left-arrow.png" alt=""></a>
                    <p class="name-record">X-Ray from Laboratory</p>                
                </div>
                </div>
                
                <div class="pdf-files">
                    <embed class="files" src="pdf/Xray.pdf" type="application/pdf">
                </div>
            </div>            -->
            <?php 
               // Get the file and subject from the URL
                $file = isset($_GET['file']) ? $_GET['file'] : '';
                $subject = isset($_GET['subject']) ? $_GET['subject'] : 'Unknown';

                // Validate file path
                if (empty($file)) {
                    echo "<p>Invalid file request.</p>";
                    exit;
                }
                ?>
                <div class="header-record">
                    <div class="info-file">
                        <a class="info-a" href="Patient-record.php"><img src="icons/patient/left-arrow.png" alt=""></a>
                        <p class="name-record"><?php echo htmlspecialchars($subject); ?></p>                
                    </div>
                </div>

                <div class="pdf-files">
                    <embed class="files" src="<?php echo htmlspecialchars($file); ?>" type="application/pdf" width="100%" height="600px">
                </div> 
        </div>
    </div>   
</body>
</html>


