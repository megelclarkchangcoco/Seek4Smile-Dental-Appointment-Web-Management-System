<?php 
    include 'php/connection.php';
    session_start();

    if(!isset($_SESSION['DentistID'])){
        header("Location: login.php");
        exit;
    }

    $dentistID = $_SESSION['DentistID'];
    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Query to get approved appointments for the current dentist
    $query = "SELECT a.AppointmentID, a.PatientID, COALESCE(p.Firstname, 'Unknown') AS PatientName, 
                     a.AppointmentDate, a.TimeStart, a.TimeEnd, 
                     a.Reason, a.AppointmentStatus 
              FROM appointment a
              LEFT JOIN patient p ON a.PatientID = p.PatientID
              WHERE a.DentistID = '$dentistID' AND a.AppointmentStatus = 'Approved'
              ORDER BY a.AppointmentDate, a.TimeStart";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Error in SQL Query: " . mysqli_error($connection));
    }

    // Fetch appointments into an array for JavaScript
    $appointments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $appointments[] = [
            'title' => htmlspecialchars($row['PatientName'] . ' - ' . $row['Reason']),
            'start' => $row['AppointmentDate'] . 'T' . $row['TimeStart'],
            'patient' => htmlspecialchars($row['PatientName']),
            'time' => date('h:i A', strtotime($row['TimeStart'])) . ' - ' . date('h:i A', strtotime($row['TimeEnd'])),
            'status' => htmlspecialchars($row['AppointmentStatus']),
            'reason' => htmlspecialchars($row['Reason'])
        ];
    }

    // Query for upcoming approved appointments (within the next 7 days, for example)
    $today = date('Y-m-d');
    $sevenDaysLater = date('Y-m-d', strtotime('+7 days'));
    $upcomingQuery = "SELECT a.AppointmentID, COALESCE(p.Firstname, 'Unknown') AS PatientName, 
                             a.AppointmentDate, a.TimeStart, a.TimeEnd, a.Reason 
                      FROM appointment a
                      LEFT JOIN patient p ON a.PatientID = p.PatientID
                      WHERE a.DentistID = '$dentistID' AND a.AppointmentStatus = 'Approved'
                      AND a.AppointmentDate BETWEEN '$today' AND '$sevenDaysLater'
                      ORDER BY a.AppointmentDate, a.TimeStart";

    $upcomingResult = mysqli_query($connection, $upcomingQuery);

    if (!$upcomingResult) {
        die("Error in SQL Query for upcoming appointments: " . mysqli_error($connection));
    }

    $upcomingAppointments = [];
    while ($row = mysqli_fetch_assoc($upcomingResult)) {
        $upcomingAppointments[] = [
            'date' => date('Y-m-d', strtotime($row['AppointmentDate'])),
            'day' => date('D', strtotime($row['AppointmentDate'])),
            'time' => date('h:i A', strtotime($row['TimeStart'])) . ' - ' . date('h:i A', strtotime($row['TimeEnd'])),
            'patient' => htmlspecialchars($row['PatientName']),
            'reason' => htmlspecialchars($row['Reason'])
        ];
    }

    // Query to get completed appointments for the current dentist
    $completedAppointmentsQuery = "SELECT a.AppointmentID, a.PatientID, COALESCE(p.Firstname, 'Unknown') AS PatientName, 
                                        a.PaymentType, COALESCE(ab.PaymentStatus, 'Unpaid') AS PaymentStatus, 
                                        a.AppointmentDate, a.TimeStart, a.TimeEnd, a.CreatedAt, 
                                        COALESCE(d.Firstname, 'Not Assigned') AS DentistName, 
                                        a.Reason, a.AppointmentStatus 
                                    FROM appointment a
                                    LEFT JOIN appointmentbilling ab ON a.AppointmentID = ab.AppointmentID
                                    LEFT JOIN patient p ON a.PatientID = p.PatientID
                                    LEFT JOIN dentist d ON a.DentistID = d.DentistID
                                    WHERE a.DentistID = '$dentistID' AND a.AppointmentStatus = 'Completed'
                                    ORDER BY a.CreatedAt DESC";

    $completedAppointmentsResult = mysqli_query($connection, $completedAppointmentsQuery);

    if (!$completedAppointmentsResult) {
        die("Error in SQL Query: " . mysqli_error($connection));
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <link rel="stylesheet" href="css/da_appointment_requests.css">
    <link rel="stylesheet" href="css/da_appointment_listview.css">
    <link rel="stylesheet" href="css/da_appointment_calendarview.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <!--JS calendar-->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.css">
    <!-- FullCalendar JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/main.min.js"></script>
    <!-- Load jQuery once -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Dental Assistant - Appointments</title>
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        /* Your existing styles remain unchanged */
        #header_with_selection {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 40px;
            background-color: white;
            border-bottom: 1px solid #e0e0e0;
            padding-top: 34px;
            padding-bottom: 0px;
            width: 100%;
        }
        .profile_left {
            display: flex;
            align-items: center;
            gap: 3px;
            justify-content: center;
            padding-bottom: 22px;
        }
        #patient-section {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        #header {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        #profile {
            display: flex;
            align-items: center;
        }
        #profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-right: 20px;
        }
        #profile-info h2 {
            margin: 0;
            color: #007bff;
        }
        #tabs {
            display: flex;
            margin-top: 10px;
            border-bottom: 2px solid #ddd;
        }
        #tabs a {
            padding: 10px 15px;
            text-decoration: none;
            color: #0085AA;
        }
        #tabs .active {
            border-bottom: 3px solid #0085AA;
            font-weight: bold;
        }
        #calendar_view_section {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            padding: 20px;
            background-color: #f5f8fa;
            gap: 15px;
            position: relative;
        }
        #calendar {
            width: 70%;
            max-width: 1200px;
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            height: 800px;
        }
        #right_appointment_panel {
            width: 27%;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: absolute;
            top: 20px;
            right: 30px;
            margin-right: 10px;
        }
        #appointment_overview,
        #upcoming_appointments {
            background: #ffffff;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        #appointment_overview h2,
        #upcoming_appointments h2 {
            font-size: 18px;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 8px;
            text-align: center;
        }
        #appointment_details p {
            margin: 5px 0;
            font-size: 14px;
            text-align: left;
        }
        #upcoming_list {
            list-style: none;
            padding: 0;
        }
        #upcoming_list li {
            padding: 10px;
            margin-bottom: 8px;
            background: #fff;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body> 

    <!-- main div -->   
    <div id="wrapper">

        <!-- Left panel -->
        <div id="left_panel">
            <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo">
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

        <!-- Right panel -->
        <div id="right_panel">
            <!-- Header with section links -->
            <div id="header_with_selection">
                <div class="sub-navigation" style="margin-left: 20px;">
                    <a href="#" onclick="showRightPanelSection('calendar_view_section')">Calendar View</a>
                    <a href="#" onclick="showRightPanelSection('request_section')">Pending</a>
                    <a href="#" onclick="showRightPanelSection('list_view_section')">List View</a>
                </div>
                <div class="profile_left">
                    <div id="info">
                        <p id="fullname">Dr. <?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                        <p id="status">Dentist</p>
                    </div>
                    <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
                </div>
            </div>

            <!-- Request Section -->
            <div id="request_section" class="section">
                <div class="main_content">
                    <h1>Approved Appointments</h1>
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <input type="text" id="input-box-request" placeholder="Search appointments" autocomplete="off">  
                            </div>
                        </div>
                        <button id="search_btn">SEARCH</button>
                    </div>
                    <table class="appointment-requests">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Requested Date</th>
                                <th>Requested Time</th>
                                <th>Date of Request</th>
                                <th>Requested Dentist</th>
                                <th>Reason for Booking the Appointment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $appointmentID = htmlspecialchars($row['AppointmentID']);
                                    ?>
                                    <tr id="row_<?php echo $appointmentID; ?>">
                                        <td><?php echo htmlspecialchars($row['PatientID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
                                        <td><div class="payment_type"><?php echo htmlspecialchars($row['PaymentType']); ?></div></td>
                                        <td><div class="approved"><?php echo htmlspecialchars($row['PaymentStatus']); ?></div></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['AppointmentDate'])); ?></td>
                                        <td class="cell_centered"><?php echo date("h:i A", strtotime($row['TimeStart'])) . ' - ' . date("h:i A", strtotime($row['TimeEnd'])); ?></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['CreatedAt'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['DentistName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Reason']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="10" style="text-align:center;">No approved appointments found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calendar View Section -->
            <div id="calendar_view_section" class="section">
                <div id="calendar"></div>
                <div id="right_appointment_panel">
                    <!-- Appointment Overview -->
                    <div id="appointment_overview">
                        <h2>Overview</h2>
                        <div id="appointment_details">
                            <p><strong>Patient Name:</strong> <span id="patient_name">-</span></p>
                            <p><strong>Date:</strong> <span id="appointment_date">-</span></p>
                            <p><strong>Time:</strong> <span id="appointment_time">-</span></p>
                            <p><strong>Status:</strong> <span id="appointment_status">-</span></p>
                            <p><strong>Session:</strong> <span id="appointment_reason">-</span></p>
                        </div>
                    </div>
                    <!-- Upcoming Appointments -->
                    <div id="upcoming_appointments">
                        <h2>Upcoming</h2>
                        <ul id="upcoming_list">
                            <?php 
                            if (empty($upcomingAppointments)) {
                                echo '<li>No upcoming approved appointments.</li>';
                            } else {
                                foreach ($upcomingAppointments as $appointment) {
                                    echo "<li><strong>{$appointment['date']} ({$appointment['day']})</strong><br>{$appointment['time']}<br><small>{$appointment['patient']} - {$appointment['reason']}</small></li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- List View Section -->
            <div id="list_view_section" class="section">
                <div class="main_content">
                    <h1>Completed Appointments</h1>
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <input type="text" id="input-box-list" placeholder="Search by Name, ID, or Payment Status" autocomplete="off">  
                            </div>
                        </div>
                        <button id="search_btn">SEARCH</button>
                    </div>
                    <table class="appointments-table">
                        <thead>
                            <tr>    
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Requested Date</th>
                                <th>Requested Time</th>
                                <th>Date of Request</th>
                                <th>Requested Dentist</th>
                                <th>Appointment Status</th>
                                <th>Reason for Booking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($completedAppointmentsResult) > 0) {
                                while ($row = mysqli_fetch_assoc($completedAppointmentsResult)) {
                                    $appointmentID = htmlspecialchars($row['AppointmentID']);
                                    ?>
                                    <tr id="row_<?php echo $appointmentID; ?>" 
                                        data-patient-id="<?php echo htmlspecialchars($row['PatientID']); ?>" 
                                        data-patient-name="<?php echo htmlspecialchars($row['PatientName']); ?>" 
                                        data-payment-status="<?php echo htmlspecialchars($row['PaymentStatus']); ?>" 
                                        data-dentist-name="<?php echo htmlspecialchars($row['DentistName']); ?>" 
                                        data-reason="<?php echo htmlspecialchars($row['Reason']); ?>">
                                        <td><?php echo htmlspecialchars($row['PatientID']); ?></td>
                                        <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
                                        <td><div class="payment_type"><?php echo htmlspecialchars($row['PaymentType']); ?></div></td>
                                        <td><div class="completed"><?php echo htmlspecialchars($row['PaymentStatus']); ?></div></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['AppointmentDate'])); ?></td>
                                        <td class="cell_centered"><?php echo date("h:i A", strtotime($row['TimeStart'])) . ' - ' . date("h:i A", strtotime($row['TimeEnd'])); ?></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['CreatedAt'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['DentistName']); ?></td>
                                        <td><div class="completed"><?php echo htmlspecialchars($row['AppointmentStatus']); ?></div></td>
                                        <td><?php echo htmlspecialchars($row['Reason']); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="11" style="text-align:center;">No completed appointments found.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation switching script -->
    <script>
        function showRightPanelSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });

            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.style.display = 'block';
            }

            // Update active state for navigation links
            document.querySelectorAll('.sub-navigation a').forEach(link => {
                link.classList.remove('active');
            });

            // Highlight the clicked link
            const clickedLink = document.querySelector(`[onclick="showRightPanelSection('${sectionId}')"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }

            // Maintain proper alignment
            document.querySelector("#right_panel").style.justifyContent = "flex-start";
        }

        // Initialize on page load
        window.onload = function () {
            document.querySelectorAll('.section').forEach(section => {
                section.style.display = 'none';
            });
            const defaultSection = document.getElementById('calendar_view_section');
            if (defaultSection) {
                defaultSection.style.display = 'block';
            }
            const defaultLink = document.querySelector('.sub-navigation a:first-child');
            if (defaultLink) {
                defaultLink.classList.add('active');
            }
        };
    </script>

    <!-- Search script for Request Section -->
    <script>
        $(document).ready(function () {
            $("#input-box-request").on("keyup", function () {
                var searchValue = $(this).val().toLowerCase();
                
                $("table.appointment-requests tbody tr").each(function () {
                    var appointmentID = $(this).find("td:eq(0)").text().toLowerCase();
                    var patientName = $(this).find("td:eq(1)").text().toLowerCase();
                    var paymentStatus = $(this).find("td:eq(3)").text().toLowerCase();
                    var dentistName = $(this).find("td:eq(7)").text().toLowerCase();
                    var reason = $(this).find("td:eq(8)").text().toLowerCase();

                    if (
                        appointmentID.includes(searchValue) || 
                        patientName.includes(searchValue) || 
                        paymentStatus.includes(searchValue) ||
                        dentistName.includes(searchValue) ||
                        reason.includes(searchValue)
                    ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $("#search_btn").click(function () {
                var searchValue = $("#input-box-request").val().toLowerCase();
                
                $("table.appointment-requests tbody tr").each(function () {
                    var appointmentID = $(this).find("td:eq(0)").text().toLowerCase();
                    var patientName = $(this).find("td:eq(1)").text().toLowerCase();
                    var paymentStatus = $(this).find("td:eq(3)").text().toLowerCase();
                    var dentistName = $(this).find("td:eq(7)").text().toLowerCase();
                    var reason = $(this).find("td:eq(8)").text().toLowerCase();

                    if (
                        appointmentID.includes(searchValue) || 
                        patientName.includes(searchValue) || 
                        paymentStatus.includes(searchValue) ||
                        dentistName.includes(searchValue) ||
                        reason.includes(searchValue)
                    ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <!-- Search script for List View Section -->
    <script>
        $(document).ready(function () {
            function performSearch(searchValue) {
                searchValue = searchValue.toLowerCase();
                
                $("table.appointments-table tbody tr").each(function () {
                    var appointmentID = $(this).data('patient-id') ? $(this).data('patient-id').toLowerCase() : '';
                    var patientName = $(this).data('patient-name') ? $(this).data('patient-name').toLowerCase() : '';
                    var paymentStatus = $(this).data('payment-status') ? $(this).data('payment-status').toLowerCase() : '';
                    var dentistName = $(this).data('dentist-name') ? $(this).data('dentist-name').toLowerCase() : '';
                    var reason = $(this).data('reason') ? $(this).data('reason').toLowerCase() : '';

                    var cellText = $(this).text().toLowerCase().replace(/<[^>]+>/g, '');

                    if (
                        appointmentID.includes(searchValue) || 
                        patientName.includes(searchValue) || 
                        paymentStatus.includes(searchValue) ||
                        dentistName.includes(searchValue) ||
                        reason.includes(searchValue) ||
                        cellText.includes(searchValue)
                    ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            $("#input-box-list").on("keyup", function () {
                var searchValue = $(this).val();
                performSearch(searchValue);
            });

            $("#search_btn").click(function () {
                var searchValue = $("#input-box-list").val();
                performSearch(searchValue);
            });
        });
    </script>

    <!-- FullCalendar Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: <?php echo json_encode($appointments); ?>,
                eventClick: function (info) {
                    document.getElementById('patient_name').innerText = info.event.extendedProps.patient;
                    document.getElementById('appointment_date').innerText = info.event.start.toDateString();
                    document.getElementById('appointment_time').innerText = info.event.extendedProps.time;
                    document.getElementById('appointment_status').innerText = info.event.extendedProps.status;
                    document.getElementById('appointment_reason').innerText = info.event.extendedProps.reason;
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>
