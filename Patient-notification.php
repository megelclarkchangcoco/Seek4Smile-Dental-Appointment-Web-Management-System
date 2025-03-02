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
    <title>Notification</title>
</head>
<body> 
    <!-- Main div -->   
    <div id="wrapper">

        <!-- This is the left panel -->
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

        <!-- This is the right panel -->
        <div id="right_panel">

            <!-- Header where the profile icon is located -->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname)?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Profile Icon">
            </div>

            <div class="notification-p">
                <p>Notification</p>
            </div>

            <!-- Notification Wrapper -->
            <div class="notification-wrapper">
                <?php 
                    // Query to fetch all appointments for the logged-in user
                    $sql = "SELECT * FROM appointment WHERE PatientID = '$patientID' ORDER BY CreatedAt DESC";
                    $result = $connection->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $appointmentStatus = strtolower($row['AppointmentStatus']);
                            $appointmentID = $row['AppointmentID'];
                            $date = date("M j", strtotime($row['AppointmentDate']));

                            if ($appointmentStatus == 'approved') {
                                echo '<div class="notification-container">
                                        <div class="notification-image">
                                            <img src="icons/patient/check_icon.png" class="sign_icon">
                                        </div>
                                        <div class="alert">
                                            <h2>Appointment Approved</h2>
                                            <p>Your Appointment has been approved. Please remember to arrive on time.</p>
                                            <p class="date">' . $date . '</p>
                                            <p class="appointmentid">ID: ' . $appointmentID . '</p>
                                        </div>
                                    </div>';
                            } elseif ($appointmentStatus == 'ongoing') {
                                echo '<div class="notification-container">
                                        <div class="notification-image">
                                            <img src="icons/patient/ongoing.png" class="sign_icon">
                                        </div>
                                        <div class="alert">
                                            <h2>Appointment Ongoing</h2>
                                            <p>Your appointment is happening now. Please proceed to your appointment location.</p>
                                            <p class="date">' . $date . '</p>
                                            <p class="appointmentid">ID: ' . $appointmentID . '</p>
                                        </div>
                                    </div>';
                            } elseif ($appointmentStatus == 'completed') {
                                echo '<div class="notification-container">
                                        <div class="notification-image">
                                            <img src="icons/patient/check_icon.png" class="sign_icon">
                                        </div>
                                        <div class="alert">
                                            <h2>Appointment Completed</h2>
                                            <p>Your appointment has been successfully completed. Thank you!</p>
                                            <p class="date">' . $date . '</p>
                                            <p class="appointmentid">ID: ' . $appointmentID . '</p>
                                        </div>
                                    </div>';
                            } elseif ($appointmentStatus == 'penalty') {
                                echo '<div class="notification-container">
                                        <div class="notification-image">
                                            <img src="icons/patient/warning_icon.png" class="sign_icon">
                                        </div>
                                        <div class="alert">
                                            <h2>Appointment Penalty Notice</h2>
                                            <p>You did not arrive on time. A penalty of ₱2000 has been applied.</p>
                                            <p class="date">' . $date . '</p>
                                            <p class="appointmentid">ID: ' . $appointmentID . '</p>
                                        </div>
                                    </div>';
                            }
                        }
                    } else {
                        echo '<p>No appointment found.</p>';
                    }

                    // Close the database connection
                    $connection->close();
                ?>
            </div>

        </div>
    </div>
    <script>
    function updateAppointments() {
        fetch('php/update_appointments.php')
            .then(response => response.json())
            .then(data => {
                console.log("Appointments updated:", data);
                location.reload(); // Refresh page to show new statuses
            })
            .catch(error => console.error("Error updating appointments:", error));
    }

    // Run the update function every 30 seconds
    setInterval(updateAppointments, 30000);
</script>
   
</body>
</html>

<!-- <div class="notification-container">
    <div class="notification-image">
        <img src="icons/patient/x_icon.png" class="sign_icon">
    </div>
    <div class="alert">
        <h2>Appointment Approved</h2>
        <p>Your Appointment has been approved please remember to arrive on time and bring any necessary documents.</p>
        <p class="date">nov 19</p>
    </div>
</div> -->