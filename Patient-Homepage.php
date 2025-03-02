<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['PatientID'])) {
        header("Location: login.php");
        exit;
    }

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
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/patientstyle.css">
    <title>HomePage</title>
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
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="booking-container">
                <div class="booking-inner">
                    <h3 class="booking-title">Booking Instructions</h3>
                    <div class="steps-container">
                        <div class="progress-bar"></div>
                        <div class="steps">
                            <div class="step">
                                <div class="circle">1</div>
                                <h4>Find Your Doctor</h4>
                                <p>You can search by the doctor's name or browse by specialization to find the right healthcare provider.</p>
                            </div>
                            <div class="step">
                                <div class="circle">2</div>
                                <h4>Select a Schedule</h4>
                                <p>Choose a date and time that fits your availability. Once you're ready, confirm your appointment to secure your slot.</p>
                            </div>
                            <div class="step">
                                <div class="circle">3</div>
                                <h4>Choose payment method</h4>
                                <p>Decide how you'd like to pay, either through HMO or cash. Make sure to complete this step to finalize your booking.</p>
                            </div>
                            <div class="step">
                                <div class="circle">4</div>
                                <h4>View your appointment status</h4>
                                <p>You can always view your appointment details and track its status.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="greetings">
                <h3>Good day, <?php echo htmlspecialchars($firstname . '!');?></h3>
            </div>

            <div class="slogan-container">
                <div class="slogan-content">
                    <div class="slogan-h3">
                        <h3>Book an appointment</h3>
                    </div>
                    <div class="slogan-p">
                        <p>Book anytime, anywhere.</p>
                    </div>
                </div>
            </div>
            
            <div class="search-by">
                <p>Seacrh by</p>
                <label class="label" onclick="redirectToBrowse()">Provider</label> 
                <label class="label" onclick="redirectToBrowse()">Specialization</label>
            </div>
        
            <div class="search-container"> 
                <form method="GET" action="Patient-browse.php">
                    <div class="search-field"> 
                        <img src="icons/patient/search_icon.png" alt="Search Icon" class="search-icon" width="20" height="20"> 
                        <input type="text" name="search" class="search-input" placeholder="Search by doctor name or specialization" 
                               value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"> 
                    </div>
                </form>
                <button type="submit" class="search-button">Search</button> 
            </div>

            <div class="text-container">
                <h3>Browse by Specialization</h3>
            </div>

            <div class="specialization-container">
                <a href="Patient-browse.php?specialization=Orthodontics" class="specialization-item">
                    <div class="image-overlay">
                        <img src="icons/patient/Orthodontics.png" alt="Orthodontics">
                    </div>
                    <p>Orthodontics</p>
                </a>
                <a href="Patient-browse.php?specialization=Periodontics" class="specialization-item">
                    <div class="image-overlay">
                        <img src="icons/patient/periodontics.png" alt="Periodontics">
                    </div>
                    <p>Periodontics</p>
                </a>
                <a href="Patient-browse.php?specialization=Endodontics" class="specialization-item">
                    <div class="image-overlay">
                        <img src="icons/patient/endodontics.png" alt="Endodontics">
                    </div>
                    <p>Endodontics</p>
                </a>
                <a href="Patient-browse.php?specialization=Oral and Maxillofacial Surgery" class="specialization-item">
                    <div class="image-overlay">
                        <img src="icons/patient/peduatruc.png" alt="Oral and Maxillofacial Surgery">
                    </div>
                    <p>Pediatric Dentistry</p>
                </a>
                <a href="Patient-browse.php?specialization=Oral and Maxillofacial Surgery" class="specialization-item">
                    <div class="image-overlay">
                        <img src="icons/patient/oral.png" alt="Oral and Maxillofacial Surgery">
                    </div>
                    <p>Oral and Maxillofacial Surgery</p>
                </a>
            </div>

        </div>
    </div>   
</body>
</html>
<script>
    function redirectToBrowse() { 
        window.location.href = 'Patient-browse.php'; 
        } 
</script>

