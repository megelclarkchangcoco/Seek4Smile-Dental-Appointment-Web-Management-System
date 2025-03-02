<?php 
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['PatientID'])) {
        header("Location: login.php");
        exit;
    }

    $patientID = $_SESSION['PatientID'];

    // Fetch patient details
    $query = "SELECT * FROM `patient` WHERE `PatientID` = '$patientID'";
    $result = mysqli_query($connection, $query);
    $patient = mysqli_fetch_assoc($result);

    if (!$patient) {
        echo "<script>
                alert('Patient not found!');
                window.location.href = 'login.php';
              </script>";
        exit;
    }

    // Update patient details if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
        $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
        $middlename = mysqli_real_escape_string($connection, $_POST['middlename']);
        $lastname = mysqli_real_escape_string($connection, $_POST['lastname']);
        $sex = mysqli_real_escape_string($connection, $_POST['sex']);
        $birthday = mysqli_real_escape_string($connection, $_POST['birthday']);
        $houseNumberStreet = mysqli_real_escape_string($connection, $_POST['houseNumberStreet']);
        $barangay = mysqli_real_escape_string($connection, $_POST['barangay']);
        $cityMunicipality = mysqli_real_escape_string($connection, $_POST['cityMunicipality']);

        // Calculate age from birthday
        $birthday_date = new DateTime($birthday);
        $current_date = new DateTime();
        $age = $current_date->diff($birthday_date)->y;

        $update_query = "
            UPDATE `patient`
            SET `Firstname` = '$firstname', `Middlename` = '$middlename', `Lastname` = '$lastname',
                `Sex` = '$sex', `Birthday` = '$birthday', `Age` = '$age',
                `HouseNumberStreet` = '$houseNumberStreet', `Barangay` = '$barangay', `CityMunicipality` = '$cityMunicipality'
            WHERE `PatientID` = '$patientID'
        ";

        if (mysqli_query($connection, $update_query)) {
            echo "<script>alert('Profile updated successfully!'); window.location.href='Patient-profile.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
        }
    }

    // Refresh patient details
    $query = "SELECT * FROM `patient` WHERE `PatientID` = '$patientID'";
    $result = mysqli_query($connection, $query);
    $patient = mysqli_fetch_assoc($result);

    $firstname = htmlspecialchars($patient['Firstname']);
    $middlename = htmlspecialchars($patient['Middlename']);
    $lastname = htmlspecialchars($patient['Lastname']);
    $sex = htmlspecialchars($patient['Sex']);
    $age = htmlspecialchars($patient['Age']);
    $birthday = htmlspecialchars($patient['Birthday']);
    $houseNumberStreet = htmlspecialchars($patient['HouseNumberStreet']);
    $barangay = htmlspecialchars($patient['Barangay']);
    $cityMunicipality = htmlspecialchars($patient['CityMunicipality']);
    $profile_img = !empty($patient['img']) ? $patient['img'] : 'img/user_default.png';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/patientstyle.css">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <!--Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    
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
                <a href="index.php">
                    <img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>
        
        

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">
        <div class="patient-container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Patient Image">
                    </div>
                    <div class="profile-info">
                        <h1><?php echo $firstname . ' ' . $lastname; ?></h1>
                        <p><strong>ID: <?php echo $patientID; ?></p>
                    </div>
                </div>

                

                <div id="personal-info">
                    <div class="personal-info-header">
                        <h2>Personal Information</h2>
                        <div class="button-group">
                            <!-- <button id="edit-btn" class="edit-button" type="button">Edit</button> -->
                        </div>

                    </div>

                    <form method="POST" action="Patient-profile.php">
                        <div id="form-section">
                            <div class="form-column">
                                <h3>Personal Data</h3>
                                <label>First Name</label>
                                <input type="text" name="firstname" class="editable" value="<?php echo $firstname; ?>" disabled>
                                
                                <label>Middle Name</label>
                                <input type="text" name="middlename" class="editable" value="<?php echo $middlename; ?>" disabled>

                                <label>Last Name</label>
                                <input type="text" name="lastname" class="editable" value="<?php echo $lastname; ?>" disabled>

                                <label>Sex</label>
                                <input type="text" name="sex" class="editable" value="<?php echo $sex; ?>" disabled>

                                <label>Age</label>
                                <input type="text" value="<?php echo $age; ?> y/o" disabled>

                                <label>Birthday</label>
                                <input type="date" name="birthday" class="editable" value="<?php echo $birthday; ?>" disabled>
                            </div>

                            <div class="form-column">
                                <h3>Address</h3>
                                <label>House Number/Street</label>
                                <input type="text" name="houseNumberStreet" class="editable" value="<?php echo $houseNumberStreet; ?>" disabled>

                                <label>Barangay</label>
                                <input type="text" name="barangay" class="editable" value="<?php echo $barangay; ?>" disabled>

                                <label>City/Municipality</label>
                                <input type="text" name="cityMunicipality" class="editable" value="<?php echo $cityMunicipality; ?>" disabled>
                            </div>
                        </div>
                        <button id="edit-btn" class="edit-button" type="button" style="margin-top: 20px">Edit</button>
                        <button type="submit" name="save" id="save-btn" class="save-button" style="display: none; margin-top: 20px;">Save</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var inputs = document.querySelectorAll('.editable');
        var editButton = document.getElementById('edit-btn');
        var saveButton = document.getElementById('save-btn');

        // Store original values to detect actual changes
        inputs.forEach(input => {
            input.setAttribute('data-original-value', input.value.trim());
        });

        // When Edit is clicked: Enable fields & show Save button
        editButton.addEventListener("click", function () {
            inputs.forEach(input => {
                input.disabled = false;
            });

            editButton.style.display = "none";
            saveButton.style.display = "inline";
        });

        //  When Save is clicked: Validate if changes exist before submitting
        saveButton.addEventListener("click", function (event) {
            var modified = false;
            inputs.forEach(input => {
                if (input.value.trim() !== input.getAttribute('data-original-value')) {
                    modified = true;
                }
            });

            if (!modified) {
                event.preventDefault(); // Prevent form submission
                alert("No changes detected. Please modify some fields before saving.");
            }
        });
    });
</script>



</body>
</html>