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
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname)?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Profile Icon">                     
            </div>

            <div class="record-p">
                <p>Medical Records</p>
            </div>
            <div class="medical-search-wrapper">
                <div class="medical-search-field">
                    <img src="icons/patient/search_icon.png" alt="Search Icon" class="medical-search-icon" width="20" height="20">
                    <input type="text" class="medical-search-input" placeholder="Search by doctor name or specialization">
                </div>
                <button class="medical-search-button">Search</button>        
            </div>
            <?php
                // Fetch the medical records for the logged-in patient
                $query = "SELECT * FROM medicalrecord WHERE PatientID = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", $patientID);
                $stmt->execute();
                $result = $stmt->get_result();

                // Check if the patient has any medical records
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Extract filename without "file/"
                        $displayFilename = basename($row['filename']); 
                        ?>
                        <div class="medical-container">
                            <img src="icons/patient/recordtooth_icon.png" alt="">
                            <div class="medical-description">
                                <h2><?php echo htmlspecialchars($row['subject']); ?></h2>
                                <p><?php echo htmlspecialchars($row['dateSubmitted']); ?> •  
                                <?php echo htmlspecialchars($row['timeSubmitted']); ?> • 
                                <?php echo htmlspecialchars($displayFilename); ?> <!-- Displays only the filename -->
                                </p>
                            </div>
                            <div class="medical-view">
                                <a href="Patient-recordfile.php?file=<?php echo urlencode($row['filename']); ?>&subject=<?php echo urlencode($row['subject']); ?>">
                                    <button class="medical-search-button">View</button>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No medical records found.</p>";
                }
            ?>
        </div>

    </div>   
</body>

<script>
    function showDownloadMessage() {
        alert("Prescription PDF has been downloaded successfully!");
    }
</script>

</html>


