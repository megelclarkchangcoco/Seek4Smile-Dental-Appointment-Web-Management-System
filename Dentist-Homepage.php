<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['DentistID'])) {
        header("Location: login.php");
        exit;
    }

    $dentistID = $_SESSION['DentistID'];
    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Query to get the total number of patients
    $totalPatientsQuery = "SELECT COUNT(DISTINCT PatientID) AS totalPatients FROM appointment WHERE DentistID = '$dentistID'";
    $totalPatientsResult = mysqli_query($connection, $totalPatientsQuery);
    $totalPatientsRow = mysqli_fetch_assoc($totalPatientsResult);
    $totalPatients = $totalPatientsRow['totalPatients'];

    // Query to get the total number of approved appointments
    $approvedAppointmentsQuery = "SELECT COUNT(*) AS approvedAppointments FROM appointment WHERE DentistID = '$dentistID' AND AppointmentStatus = 'Completed'";
    $approvedAppointmentsResult = mysqli_query($connection, $approvedAppointmentsQuery);
    $approvedAppointmentsRow = mysqli_fetch_assoc($approvedAppointmentsResult);
    $approvedAppointments = $approvedAppointmentsRow['approvedAppointments'];

    $today = date('Y-m-d');

    // Query to get today's schedule
    $todaysScheduleQuery = "SELECT p.Firstname, p.Lastname, a.AppointmentStatus, a.TimeStart, a.TimeEnd, a.AppointmentType 
                            FROM appointment a 
                            JOIN patient p ON a.PatientID = p.PatientID 
                            WHERE a.DentistID = '$dentistID' AND a.AppointmentDate = '$today'";
    $todaysScheduleResult = mysqli_query($connection, $todaysScheduleQuery);

    // Query to get the last completed appointment
    $lastCompletedAppointmentQuery = "SELECT p.Firstname, p.Lastname, a.AppointmentType 
                                      FROM appointment a 
                                      JOIN patient p ON a.PatientID = p.PatientID 
                                      WHERE a.DentistID = '$dentistID' AND a.AppointmentStatus = 'Completed' 
                                      ORDER BY a.AppointmentDate DESC, a.TimeEnd DESC 
                                      LIMIT 1";
    $lastCompletedAppointmentResult = mysqli_query($connection, $lastCompletedAppointmentQuery);
    $lastCompletedAppointmentRow = mysqli_fetch_assoc($lastCompletedAppointmentResult);

    // Query to get the count of upcoming appointments
    $upcomingAppointmentsQuery = "SELECT COUNT(*) AS upcomingAppointments 
                                  FROM appointment 
                                  WHERE DentistID = '$dentistID' AND AppointmentDate > '$today'";
    $upcomingAppointmentsResult = mysqli_query($connection, $upcomingAppointmentsQuery);
    $upcomingAppointmentsRow = mysqli_fetch_assoc($upcomingAppointmentsResult);
    $upcomingAppointments = $upcomingAppointmentsRow['upcomingAppointments'];

    // Query to get the count of total active plans
    // $totalActivePlansQuery = "SELECT COUNT(*) AS totalActivePlans 
    //                           FROM treatment_plan 
    //                           WHERE DentistID = '$dentistID' AND PlanStatus = 'Active'";
    // $totalActivePlansResult = mysqli_query($conn, $totalActivePlansQuery);
    // $totalActivePlansRow = mysqli_fetch_assoc($totalActivePlansResult);
    // $totalActivePlans = $totalActivePlansRow['totalActivePlans'];

     // Query to get appointment status counts
     $appointmentStatusQuery = "SELECT AppointmentStatus, COUNT(*) AS count 
            FROM appointment 
            WHERE DentistID = '$dentistID' 
            GROUP BY AppointmentStatus";
        $appointmentStatusResult = mysqli_query($connection, $appointmentStatusQuery);
        $appointmentStatusData = [];
        while ($row = mysqli_fetch_assoc($appointmentStatusResult)) {
        $appointmentStatusData[$row['AppointmentStatus']] = $row['count'];
        }

        // Query to get appointment type counts
        $appointmentTypeQuery = "SELECT AppointmentType, COUNT(*) AS count 
        FROM appointment 
        WHERE DentistID = '$dentistID' 
        GROUP BY AppointmentType";
        $appointmentTypeResult = mysqli_query($connection, $appointmentTypeQuery);
        $appointmentTypeData = [];
        while ($row = mysqli_fetch_assoc($appointmentTypeResult)) {
        $appointmentTypeData[$row['AppointmentType']] = $row['count'];
        }
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentiststyle.css">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <title>Dentist - Homepage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

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

        <!-- this div where the right panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status">Dentist</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <!-- This div where the main content located-->
            <div id="content" style="background: white; border: none;">
                <!-- Header image -->
                <div class="imgheader">
                    <img src="icons/dentist/header_homepage.png" alt="Header Image">
                </div>

                <!-- Title -->
                <h1>Welcome, Dr. <?php echo htmlspecialchars($firstname)?>!</h1>

                <!-- Main Dashboard Layout -->
                <div class="tableandothers">

                    <!-- LEFT COLUMN -->
                    <div class="left_column">
                        
                        <!-- Current / Total Patients -->
                        <div class="numbers">
                            <div class="currentpatient">
                                <h2><?php echo htmlspecialchars($totalPatients) ?></h2>
                                <p>Current Patient</p>
                            </div>
                            <div class="currentpatient">
                                <h2><?php echo htmlspecialchars($approvedAppointments)?></h2>
                                <p>Total Patients</p>
                            </div>
                        </div>
                        
                        <!-- Appointment Statistics (Donut Chart) -->
                        <div class="apptstats">
                            <h2>Appointment Statistics</h2>
                            <div class="chart-container">
                                <canvas id="appointmentChart"></canvas>
                            </div>
                        </div>

                        <!-- Treatment Statistics (Donut Chart) -->
                        <div class="apptstats">
                            <h2>Treatment Statistics</h2>
                            <div class="chart-container">
                                <canvas id="treatmentChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="right_column">

                        <!-- Today's Schedule -->
                        <div class="todays_schedule">
                            <h2>Today's Schedule</h2>
                            <table class="schedule_today">
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                </tr>
                                <?php while ($row = mysqli_fetch_assoc($todaysScheduleResult)) { ?>
                                <tr>
                                    <td><?php echo $row['Firstname'] . ' ' . $row['Lastname']; ?></td>
                                    <td><?php echo $row['AppointmentStatus']; ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['TimeStart'])) . ' - ' . date('h:i A', strtotime($row['TimeEnd'])); ?></td>
                                    <td><?php echo $row['AppointmentType']; ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>

                        <!-- Bottom Right: Treatment Plans + KPI boxes -->
                        <div class="bottom_right">
                            
                            <!-- Treatment Plans Overview -->
                            <div class="treatment_plans_overview">
                                <h2>Treatment Plans Overview</h2>
                                <table class="treatmentplan_table">
                                    <tr>
                                        <th>Recent Treatments</th>
                                        <th>Patient Name</th>
                                    </tr>
                                    <?php if ($lastCompletedAppointmentRow) { ?>
                                    <tr>
                                        <td><?php echo $lastCompletedAppointmentRow['AppointmentType']; ?></td>
                                        <td><?php echo $lastCompletedAppointmentRow['Firstname'] . ' ' . $lastCompletedAppointmentRow['Lastname']; ?></td>
                                    </tr>
                                    <?php } else { ?>
                                    <tr>
                                        <td colspan="2">No completed appointments found.</td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>

                            <!-- Upcoming Appointments & Total Active Plans -->
                            <div class="numberkpis">
                                <div class="upcoming_appt">
                                    <h3>Upcoming Appointments</h3>
                                    <p><?php echo $upcomingAppointments; ?></p>
                                </div>
                                <div class="total_active_plans">
                                    <h3>Total Active Plans</h3>
                                    <p>6</p>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div> 
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Appointment Statistics Donut
    const appointmentCtx = document.getElementById('appointmentChart').getContext('2d');
    const appointmentChart = new Chart(appointmentCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($appointmentStatusData)); ?>,
            datasets: [{
                label: 'Appointments',
                data: <?php echo json_encode(array_values($appointmentStatusData)); ?>,
                backgroundColor: [
                    '#35c3a3', // Light green
                    '#ffce56', // Orange
                    '#0085aa', // Blue
                    '#cd6a30'  // Brown
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Treatment Statistics Donut
    const treatmentCtx = document.getElementById('treatmentChart').getContext('2d');
    const treatmentChart = new Chart(treatmentCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($appointmentTypeData)); ?>,
            datasets: [{
                label: 'Procedures',
                data: <?php echo json_encode(array_values($appointmentTypeData)); ?>,
                backgroundColor: [
                    '#0085aa', // Blue
                    '#ffce56', // Orange
                    '#35c3a3'  // Light green
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
     
</body>
</html>