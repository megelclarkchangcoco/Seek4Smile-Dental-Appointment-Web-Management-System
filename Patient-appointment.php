<?php
    // Include the database connection file
    include 'php/connection.php';

    // Start the session to access session variables
    session_start();

    // Check if the user is logged in by verifying if 'PatientID' is set in the session
    if (!isset($_SESSION['PatientID'])) {
        // If not logged in, redirect to the login page and exit the script
        header("Location: login.php");
        exit;
    }

    // Retrieve session variables for the logged-in patient
    $patientID = $_SESSION['PatientID']; // Patient ID from the session
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
    <title>Appointment</title>
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

            <div class="appointment-p">
                <p>Appointment</p>
            </div>
            <!-- <div class="appointment-search-wrapper">
                <div class="appointment-search-field">
                    <img src="icons/patient/search_icon.png" alt="Search Icon" class="appointment-search-icon" width="20" height="20">
                    <input type="text" class="appointment-search-input" placeholder="Search by doctor name or specialization">
                </div>
                <button class="appointment-search-button">Search</button>        
            </div> -->

            
            <div class="appointment-wrapper">
            <?php
                // Fetch all appointments for the logged-in patient
                $appointmentQuery = "SELECT a.*, p.Firstname AS PatientFirstname, p.Middlename AS PatientMiddlename, p.Lastname AS PatientLastname, 
                                            d.Firstname AS DentistFirstname, d.Middlename AS DentistMiddlename, d.Lastname AS DentistLastname
                                    FROM appointment a
                                    JOIN patient p ON a.PatientID = p.PatientID
                                    JOIN dentist d ON a.DentistID = d.DentistID
                                    WHERE a.PatientID = '$patientID'"; // Filter by the logged-in patient's ID
                $appointmentResult = mysqli_query($connection, $appointmentQuery); // Execute the query

                // Check if there are any appointments
                if (mysqli_num_rows($appointmentResult) > 0) {
                    // Loop through each appointment and display its details
                    while ($appointment = mysqli_fetch_assoc($appointmentResult)):
                        // Extract appointment details from the fetched data
                        $appointmentStatus = trim($appointment['AppointmentStatus']); 
                        $patientName = $appointment['PatientFirstname'] . ' ' . $appointment['PatientMiddlename'] . ' ' . $appointment['PatientLastname']; 
                        $dentistName = 'Dr. ' . $appointment['DentistFirstname'] . ' ' . $appointment['DentistMiddlename'] . ' ' . $appointment['DentistLastname']; 
                        $appointmentDate = date('F j, Y', strtotime($appointment['AppointmentDate'])); 
                        $appointmentTime = date('h:i A', strtotime($appointment['TimeStart'])) . ' - ' . date('h:i A', strtotime($appointment['TimeEnd'])); 
                        $paymentType = $appointment['PaymentType']; 
                        $reason = $appointment['Reason']; 
                        $createdAt = date('F j, Y', strtotime($appointment['CreatedAt'])); 
                        $appointmentID = $appointment['AppointmentID']; // Unique ID for each appointment
            ?>
            <div class="appointment-container">
                <h2 class="appointment-title">Request Appointment for <?php echo $appointment['AppointmentType']; ?></h2>
                <div class="appointment-info">
                    <strong>Patient:</strong><p><?php echo $patientName; ?></p> 
                    <strong>Doctor:</strong><p><?php echo $dentistName; ?></p> 
                </div>
                <div class="appointment-info">
                    <strong>Date:</strong><p><?php echo $appointmentDate; ?></p> 
                    <strong>Time:</strong><p><?php echo $appointmentTime; ?></p> 
                </div>
                <div class="appointment-info">
                    <strong>Payment:</strong><p><?php echo ucfirst($paymentType); ?></p> 
                </div>
                <div class="appointment-reasons">
                    <strong>Reason for Booking the Appointment:</strong><p><?php echo $reason; ?></p> 
                </div>
                <div class="appointment-footer">
                    <div class="appointment-actions">
                        <?php
                        // Logic to display buttons based on the appointment status
                        if (strtolower($appointmentStatus) == 'completed') {
                            // If the appointment is completed, show the COMPLETED button
                            echo '<button class="appointment-completed">COMPLETED</button>';
                        } elseif (strtolower($appointmentStatus) == 'accepted') {
                            // If the appointment is approved, show the APPROVED button and a CANCEL button
                            echo '<button class="appointment-approved">Approved</button>';
                            echo '<button class="appointment-cancel" onclick="openModal(\'' . htmlspecialchars($appointmentID, ENT_QUOTES) . '\')">CANCEL</button>';
                        }elseif (strtolower($appointmentStatus) == 'scheduled') {
                            // If the appointment is approved, show the APPROVED button and a CANCEL button
                            echo '<button class="appointment-cancel" onclick="openModal(\'' . htmlspecialchars($appointmentID, ENT_QUOTES) . '\')">CANCEL</button>';
                            echo '<button class="appointment-approved">scheduled</button>';
                        } elseif (strtolower($appointmentStatus) == 'canceled') {
                            // If the appointment is canceled, show the CANCELED button
                            echo '<button class="appointment-canceled">CANCELED</button>';
                        } else {
                            // For all other cases (e.g., empty or null status), show CANCEL and PENDING buttons    
                            echo '<button class="appointment-cancel" onclick="openModal(\'' . htmlspecialchars($appointmentID, ENT_QUOTES) . '\')">CANCEL</button>';

                            echo '<button class="appointment-pending">PENDING</button>';
                        }
                        ?>
                    </div>
                    <div class="appointment-date-requested">
                        <p><strong>Date Requested:</strong> <?php echo $createdAt; ?></p> 
                    </div>
                    <div class="appointment-id">
                        <p><strong>Appointment ID:</strong> <?php echo $appointmentID; ?></p>
                    </div>
                </div>
            </div>
            <?php
                    endwhile; // End of while loop
                } else {
                    // If no appointments are found, display a message
                    echo '<p>No appointments found.</p>';
                }
            ?>
        </div>

        <!-- Modal for Cancel Appointment -->
        <?php
        while ($appointment = mysqli_fetch_assoc($appointmentResult)):
        ?>
            
        <?php
        endwhile;
        ?>

        <!-- Place modal here, outside loop -->
        <div id="cancelModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&larr; Return</span>
                <h2>Cancel Appointment</h2>
                <p>Reason for Cancelling</p>
                <form id="cancelForm">
                    <label><input type="radio" name="reason" value="personal"> Personal Reasons</label><br>
                    <label><input type="radio" name="reason" value="transportation"> Transportation Issues</label><br>
                    <label><input type="radio" name="reason" value="work"> Work-related Issues</label><br>
                    <label><input type="radio" name="reason" value="change_of_mind"> Change of Mind</label><br>
                    <textarea id="otherReason" placeholder="Add another reason..."></textarea><br>
                    <input type="hidden" id="appointmentID" name="appointmentID">
                    <button type="button" onclick="submitCancel()">CONFIRM</button>
                </form>
            </div>
        </div>

    </div>
    <script>
    // Function to open the modal and set the appointment ID
    function openModal(appointmentID) {
        document.getElementById('appointmentID').value = appointmentID; // Set the appointment ID
        document.getElementById('cancelModal').style.display = 'flex'; // Use flex for proper centering
    }

    function closeModal() {
        document.getElementById('cancelModal').style.display = 'none'; // Hide modal
    }

    // Close modal when clicking outside
    window.onclick = function (event) {
        const modal = document.getElementById('cancelModal');
        if (event.target === modal) {
            closeModal();
        }
    };


    // Function to submit the cancel request
    function submitCancel() {
        const appointmentID = document.getElementById('appointmentID').value;
        const reason = document.querySelector('input[name="reason"]:checked')?.value || '';
        const otherReason = document.getElementById('otherReason').value;

        // Combine the selected reason and other reason
        const cancelationReason = otherReason ? `${reason}: ${otherReason}` : reason;

        // Validate if a reason is selected
        if (!reason && !otherReason) {
            alert('Please select a reason or provide additional details.');
            return;
        }

        // Send data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'php/cancel_appointment.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert('Appointment canceled successfully.');
                location.reload(); // Refresh the page to reflect changes
            } else {
                alert('Failed to cancel appointment.');
            }
        };
        xhr.send(`appointmentID=${appointmentID}&cancelationReason=${encodeURIComponent(cancelationReason)}`);
    }

    // Close the modal if the user clicks outside of it
    window.onclick = function (event) {
        const modal = document.getElementById('cancelModal');
        if (event.target === modal) {
            closeModal();
        }
    };
    </script> <!-- Link to your JavaScript file -->

    <script>
        // Open Payment Modal
        function openPaymentModal(billingID, totalFee, paymentStatus) {
            document.getElementById("paymentModal").style.display = "flex";
            document.getElementById("amount").max = totalFee; // Prevent Overpayment
            document.getElementById("paymentForm").dataset.billingId = billingID;
        }

        // Close Payment Modal
        function closePaymentModal() {
            document.getElementById("paymentModal").style.display = "none";
        }

        // Format Card Number Input
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '').substring(0, 16);
            value = value.replace(/(\d{4})(?=\d)/g, '$1-');
            input.value = value;
        }

        // Process Payment
        // function processPayment(event) {
        //     event.preventDefault();
            
        //     const billingID = document.getElementById("paymentForm").dataset.billingId;
        //     const cardNumber = document.getElementById("cardNum").value.replace(/-/g, '');
        //     const amount = parseFloat(document.getElementById("amount").value);
        //     const pin = document.getElementById("pin").value;
            
        //     if (isNaN(amount) || amount <= 0) {
        //         alert("Enter a valid payment amount.");
        //         return;
        //     }

        //     fetch("php/patient_process_payment.php", {
        //         method: "POST",
        //         headers: { "Content-Type": "application/json" },
        //         body: JSON.stringify({ billingID, cardNumber, amount, pin })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.status === "success") {
        //             alert("Payment successful!");
        //             closePaymentModal();
        //             location.reload();
        //         } else {
        //             alert("Error: " + data.message);
        //         }
        //     })
        //     .catch(error => console.error("Payment Error:", error));
        // }

    </script>
   
</body>
</html>
