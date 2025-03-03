<?php
include 'php/connection.php';
session_start();

if (!isset($_SESSION['AssistantID'])) {
    header("Location: login.php");
    exit;
}

$firstname = $_SESSION['Firstname'];
$lastname = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// query for display appointment scheduled list
$query = "SELECT a.AppointmentID, a.PatientID, COALESCE(p.Firstname, 'Unknown') AS PatientName, 
                 a.PaymentType, COALESCE(ab.PaymentStatus, 'Unpaid') AS PaymentStatus, 
                 a.AppointmentDate, a.TimeStart, a.TimeEnd, a.CreatedAt, 
                 COALESCE(d.Firstname, 'Not Assigned') AS DentistName, 
                 a.Reason 
          FROM appointment a
          LEFT JOIN appointmentbilling ab ON a.AppointmentID = ab.AppointmentID
          LEFT JOIN patient p ON a.PatientID = p.PatientID
          LEFT JOIN dentist d ON a.DentistID = d.DentistID
          WHERE a.appointmentStatus = 'scheduled'
          ORDER BY a.CreatedAt DESC";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Error in SQL Query: " . mysqli_error($connection));
}


// Query for displaying appointment approved or ongoing list (case-insensitive)
$query2 = "SELECT a.AppointmentID, a.PatientID, COALESCE(p.Firstname, 'Unknown') AS PatientName, 
                 a.PaymentType, COALESCE(ab.PaymentStatus, 'Unpaid') AS PaymentStatus, 
                 a.AppointmentDate, a.TimeStart, a.TimeEnd, a.CreatedAt, 
                 COALESCE(d.Firstname, 'Not Assigned') AS DentistName, 
                 a.Reason, a.AppointmentStatus 
          FROM appointment a
          LEFT JOIN appointmentbilling ab ON a.AppointmentID = ab.AppointmentID
          LEFT JOIN patient p ON a.PatientID = p.PatientID
          LEFT JOIN dentist d ON a.DentistID = d.DentistID
          WHERE LOWER(a.AppointmentStatus) IN ('approved', 'ongoing')
          ORDER BY a.CreatedAt DESC";

$result2 = mysqli_query($connection, $query2); 

if (!$result2) {
    die("Error in SQL Query (Approved Appointments): " . mysqli_error($connection)); // Debugging
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


    <title>Dental Assistant - Appointments</title>
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <style>
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

        /*Patient Profile connected in Patient CSS*/
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
     /* Ensure calendar view section aligns items properly */
    #calendar_view_section {
        display: flex;
        flex-direction: row;
        align-items: flex-start; /* Align to the top */
        justify-content: space-between; /* Space between calendar and right panel */
        padding: 20px;
        background-color: #f5f8fa;
        gap: 15px; /* Reduce gap to bring the right panel closer */
        position: relative;
    }

    /* Calendar Styling */
    #calendar {
        width: 70%; /* Make it slightly wider */
        max-width: 1200px;
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        height: 800px;
    }

    /* Right-side panel (Appointment Overview & Upcoming Appointments) */
    #right_appointment_panel {
        width: 27%; /* Adjust width */
        display: flex;
        flex-direction: column;
        gap: 15px; /* Space between sections */
        position: absolute;
        top: 20px; /* Move it higher */
        right: 30px; /* Move it closer to the calendar */
        margin-right: 10px;
    }

    /* Appointment Overview */
    #appointment_overview,
    #upcoming_appointments {
        background: #ffffff;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    /* Title Styling */
    #appointment_overview h2,
    #upcoming_appointments h2 {
        font-size: 18px;
        color: #007bff;
        border-bottom: 2px solid #007bff;
        padding-bottom: 5px;
        margin-bottom: 8px;
        text-align: center;
    }

    /* Appointment Details */
    #appointment_details p {
        margin: 5px 0;
        font-size: 14px;
        text-align: left;
    }

    /* Upcoming Appointments */
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

        <!-- this the left panel located-->
        <div id="left_panel">
            <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo"> <!-- Add your logo image path -->
        
            <label>
                <a href="Assistant-homepage.php">
                    <img src="icons/dentalassistant/dashboard_icon.png" alt="Dashboard"> Dashboard
                </a>
            </label>
            <label>
                <a href="Assistant-notification.php">
                    <img src="icons/dentalassistant/notif_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Assistant-appointment.php">
                    <img src="icons/dentalassistant/calendar_icon.png" alt="Appointments"> Appointments
                </a>
            </label>
            <label>
                <a href="Assistant-patients.php">
                    <img src="icons/dentalassistant/patient_icon.png" alt="Patients"> Patients
                </a>
            </label>
            <label>
                <a href="Assistant-inventory.php">
                    <img src="icons/dentalassistant/inventory_icon.png" alt="Inventory"> Inventory
                </a>
            </label>
            <label>
                <a href="Assistant-profile.php">
                    <img src="icons/dentalassistant/profile_icon.png" alt="Profile"> Profile
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
            <div id="header_with_selection">
                <div class="sub-navigation" style="margin-left: 20px;">
                    <a href="#" onclick="showRightPanelSection('request_section')">Request</a>
                    <a href="#" onclick="showRightPanelSection('list_view_section')">List View</a>
                    <a href="#" onclick="showRightPanelSection('calendar_view_section')">Calendar View</a>
                </div>
                <div class="profile_left">
                    <div id="info">
                        <p id="fullname"><?= htmlspecialchars($firstname) ?> <?= htmlspecialchars($lastname) ?></p>
                        <p id="status">Dental Assistant</p>
                    </div>
                    <img id="profile_icon" src="<?= htmlspecialchars($profile_img) ?>" alt="Profile Icon">

                </div>
                
            </div>

             <!-- request section-->
            <div id="request_section" class="section">
                <!-- Request  sections -->
                <div class="main_content">
                    <h1>Appointment Requests</h1>
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <input type = "text" id ="input-box" placeholder="Search appointments" autocomplete="off">  
                            </div>
                        </div>
                        
                        <button id="search_btn">  SEARCH</button>
    
                        </div>    
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
                                <th>Actions</th>
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
                                        <td><div class="paid" style="align-items: center; text-align: center;"><?php echo htmlspecialchars($row['PaymentStatus']); ?></div></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['AppointmentDate'])); ?></td>
                                        <td class="cell_centered"><?php echo date("h:i A", strtotime($row['TimeStart'])) . ' - ' . date("h:i A", strtotime($row['TimeEnd'])); ?></td>
                                        <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['CreatedAt'])); ?></td>
                                        <td><?php echo htmlspecialchars($row['DentistName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Reason']); ?></td>
                                        <td class="cell_center_content">
                                            <button class="approve_btn" data-id="<?php echo $appointmentID; ?>">
                                                <span class="material-symbols-outlined">check</span> Approve
                                            </button>
                                            
                                            <button class="cancel_btn" data-id="<?php echo $appointmentID; ?>">
                                                <span class="material-symbols-outlined">cancel</span> Cancel
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="10" style="text-align:center;">No pending appointment requests.</td></tr>';
                            }
                            ?>
                        </tbody>

                    </table>

            </div>

            <!-- List View Section -->
            <div id="list_view_section" class="section">
                <div class="main_content">
                    <h1>Appointment List</h1>
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <input type="text" id="input-box" placeholder="Search by Name, ID, or Payment Status" autocomplete="off">  
                            </div>
                        </div>
                        <button id="search_btn">SEARCH</button>
                    </div>    
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (mysqli_num_rows($result2) > 0) { 
                            while ($row = mysqli_fetch_assoc($result2)) {
                                $appointmentID = htmlspecialchars($row['AppointmentID']);
                                ?>
                                <tr id="row_<?php echo $appointmentID; ?>">
                                    <td><?php echo htmlspecialchars($row['PatientID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['PatientName']); ?></td>
                                    <td><div class="payment_type"><?php echo htmlspecialchars($row['PaymentType']); ?></div></td>
                                    <td><div class="paid"><?php echo htmlspecialchars($row['PaymentStatus']); ?></div></td>
                                    <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['AppointmentDate'])); ?></td>
                                    <td class="cell_centered"><?php echo date("h:i A", strtotime($row['TimeStart'])) . ' - ' . date("h:i A", strtotime($row['TimeEnd'])); ?></td>
                                    <td class="cell_centered"><?php echo date("Y-m-d", strtotime($row['CreatedAt'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['DentistName']); ?></td>
                                    <td><div class="approved"><?php echo htmlspecialchars($row['AppointmentStatus']); ?></div></td>
                                    <td><?php echo htmlspecialchars($row['Reason']); ?></td>
                                    <td class="action_appointment">
                                        <button class="mark_as_complete" data-id="<?php echo $appointmentID; ?>">
                                            <span class="material-symbols-outlined">check</span> Mark as Complete
                                        </button>

                                        <button class="penalty_btn" data-id="<?php echo $appointmentID; ?>" style="background-color: white; color: red; border-radius: 5px; font-size: 14px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; border-color: red;"                                        >
                                            <span class="material-symbols-outlined">cancel</span> Penalty
                                        </button>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="11" style="text-align:center;">No approved appointments.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

                <!-- Calendar View Section -->
            <div id="calendar_view_section" class="section">
                <div id="calendar">

                </div>

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
                        <ul id="upcoming_list"></ul>
                    </div>
                </div>
            </div>

    
      
    </div>

        
    <script>
    function showRightPanelSection(sectionId) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none'; // Hide all sections
        });

        // Show the selected section
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) {
            selectedSection.style.display = 'block'; // Make the selected section visible
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
        // Hide all sections except the first one (default is 'request_section')
        document.querySelectorAll('.section').forEach(section => {
            section.style.display = 'none';
        });

        // Show only the default section (Appointment Requests)
        const defaultSection = document.getElementById('request_section');
        if (defaultSection) {
            defaultSection.style.display = 'block';
        }

        // Set default active link
        const defaultLink = document.querySelector('.sub-navigation a:first-child');
        if (defaultLink) {
            defaultLink.classList.add('active');
        }
    };
</script>


    <!-- Script for approved and cancel-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".approve_btn").click(function () {
                var appointmentID = $(this).data("id"); 
                var confirmAction = confirm("Are you sure you want to approve this appointment?");
                if (confirmAction) {
                    updateAppointmentStatus(appointmentID, "Approved");
                }
            });

            $(".cancel_btn").click(function () {
                var appointmentID = $(this).data("id"); 
                var confirmAction = confirm("Are you sure you want to cancel this appointment?");
                if (confirmAction) {
                    updateAppointmentStatus(appointmentID, "Canceled");
                }
            });

            function updateAppointmentStatus(appointmentID, status) {
                console.log("Sending AJAX | Appointment ID:", appointmentID, "| Status:", status); 

                $.ajax({
                    url: "php/update_appointment.php",
                    type: "POST",
                    data: { appointmentID: appointmentID, status: status },
                    success: function (response) {
                        console.log("Server Response:", response); 
                        if (response.trim() === "success") {
                            alert("Appointment successfully updated!");
                            $("#row_" + appointmentID).fadeOut("slow", function () {
                                $(this).remove();
                            });
                        } else {
                            alert("Error updating status!");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX Error:", error); 
                        alert("AJAX request failed!");
                    }
                });
            }
        });
    </script>
    <!--script for search for appointment request-->
    <script>
        $(document).ready(function () {
            $("#input-box").on("keyup", function () {
                var searchValue = $(this).val().toLowerCase();
                
                $("table tbody tr").each(function () {
                    var appointmentID = $(this).find("td:eq(0)").text().toLowerCase();
                    var patientName = $(this).find("td:eq(1)").text().toLowerCase();
                    var paymentStatus = $(this).find("td:eq(3)").text().toLowerCase();

                    if (
                        appointmentID.includes(searchValue) || 
                        patientName.includes(searchValue) || 
                        paymentStatus.includes(searchValue)
                    ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $("#search_btn").click(function () {
                var searchValue = $("#input-box").val().toLowerCase();
                
                $("table tbody tr").each(function () {
                    var appointmentID = $(this).find("td:eq(0)").text().toLowerCase();
                    var patientName = $(this).find("td:eq(1)").text().toLowerCase();
                    var paymentStatus = $(this).find("td:eq(3)").text().toLowerCase();

                    if (
                        appointmentID.includes(searchValue) || 
                        patientName.includes(searchValue) || 
                        paymentStatus.includes(searchValue)
                    ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <!-- Script for appointment list approved to completed-->
    <script>
        $(document).ready(function () {
            // Mark as Complete
            $(document).on("click", ".mark_as_complete", function () {
                var appointmentID = $(this).data("id");
                var confirmAction = confirm("Are you sure you want to mark this appointment as completed?");
                if (confirmAction) {
                    updateAppointmentStatus(appointmentID, "Completed");
                }
            });

            // Penalty Button (if you wish to handle it differently, you can modify accordingly)
            $(document).on("click", ".penalty_btn", function () {
                var appointmentID = $(this).data("id");
                var confirmAction = confirm("Are you sure you want to mark this appointment as a penalty?");
                if (confirmAction) {
                    updateAppointmentStatus(appointmentID, "penalty");
                }
            });

            // Function to update appointment status via AJAX
            function updateAppointmentStatus(appointmentID, status) {
                $.ajax({
                    url: "php/update_appointment_status.php",
                    type: "POST",
                    data: { appointmentID: appointmentID, status: status },
                    success: function (response) {
                        if (response.trim() === "success") {
                            alert("Appointment updated successfully!");
                            if (status === "Completed") {
                                // Fade out and remove the row when marked as completed
                                $("#row_" + appointmentID).fadeOut("slow", function () {
                                    $(this).remove();
                                });
                            } else {
                                // Optionally update the status cell (if needed)
                                $("#row_" + appointmentID + " .approved").text(status);
                            }
                        } else {
                            alert("Error updating status!");
                        }
                    },
                    error: function () {
                        alert("AJAX request failed!");
                    }
                });
            }
        });
        </script>


    <!--JS calendar for display list and upcomming-->
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
            events: 'php/fetch_appointments.php', // Fetch appointments dynamically
            eventClick: function (info) {
                // Update the appointment overview section
                document.getElementById('patient_name').innerText = info.event.extendedProps.patient;
                document.getElementById('appointment_date').innerText = info.event.start.toDateString();
                document.getElementById('appointment_time').innerText = info.event.extendedProps.time;
                document.getElementById('appointment_status').innerText = info.event.extendedProps.status;
                document.getElementById('appointment_reason').innerText = info.event.extendedProps.reason;
            }
        });

        calendar.render();

        // Fetch upcoming appointments
        fetch("php/fetch_upcoming.php")
            .then(response => response.json())
            .then(data => {
                let upcomingList = document.getElementById('upcoming_list');
                upcomingList.innerHTML = ''; // Clear previous items

                if (data.length === 0) {
                    upcomingList.innerHTML = "<li>No upcoming appointments.</li>";
                    return;
                }

                data.forEach(appointment => {
                    let listItem = document.createElement("li");
                    listItem.innerHTML = `
                        <strong>${appointment.date}</strong> (${appointment.day}) <br>
                        ${appointment.time} <br>
                        <small>${appointment.patient} - ${appointment.reason}</small>
                    `;
                    upcomingList.appendChild(listItem);
                });
            })
            .catch(error => console.error("Error fetching upcoming appointments:", error));
    });
    </script>


</body>
</html>