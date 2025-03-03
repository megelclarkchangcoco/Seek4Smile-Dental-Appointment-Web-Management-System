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

    // Fetch all dentists with their working hours
    $query = "
    SELECT d.DentistID, d.Firstname, d.Middlename, d.Lastname, d.Email, d.img, d.Specialization, d.Description, d.YearExperience,
        w.Monday, w.Tuesday, w.Wednesday, w.Thursday, w.Friday, w.Saturday, w.Sunday
    FROM dentist d
    LEFT JOIN dentist_working_hour w ON d.DentistID = w.DentistID
    ";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    }
    
    // Fetch dentists
    $query2 = "SELECT DentistID, Firstname, Middlename, Lastname FROM dentist";
    $result2 = mysqli_query($connection, $query2);
    if (!$result2) {
        die("Query Failed: " . mysqli_error($connection));
    }

    // Fetch pricing data from the database
    $query = "SELECT * FROM appointment_pricing";
    $result3 = mysqli_query($connection, $query);

    $pricingData = [];

    if ($result3 && mysqli_num_rows($result3) > 0) {
        while ($row = mysqli_fetch_assoc($result3)) {
            $type = $row['AppointmentType'];
            $subCategory = $row['SubCategory'];
            $price = $row['Price'];

            if (!isset($pricingData[$type])) {
                $pricingData[$type] = [];
            }
            if (!empty($subCategory)) {
                $pricingData[$type][$subCategory] = $price;
            }
        }
    }

    // Free the result set
    mysqli_free_result($result3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/patientstyle.css">
    <title>Browse</title>
</head>
<body> 
    <!-- main div -->   
    <div id="wrapper">
        <!-- this the left panel located-->
        <div id="left_panel">
            <img id="logo" src="icons/patient/logo_seek4smiles.png" alt="Logo">
            <label><a href="Patient-Homepage.php"><img src="icons/patient/home_icon.png" alt="Home"> Homepage</a></label>
            <label><a href="Patient-notification.php"><img src="icons/patient/notification_icon.png" alt="Notifications"> Notifications</a></label>
            <label><a href="Patient-record.php"><img src="icons/patient/medical_record_icon.png" alt="Medical Records"> Medical Records</a></label>
            <label><a href="Patient-prescription.php"><img src="icons/patient/prescription.png" alt="Medical Records"> Prescription Records</a></label>
            <label><a href="Patient-appointment.php"><img src="icons/patient/calendar_icon.png" alt="Appointments"> Appointments</a></label>
            <label><a href="Patient-message.php"><img src="icons/patient/message_icon.png" alt="Messages"> Messages</a></label>
            <label><a href="Patient-billing.php"><img src="icons/patient/billing_icons.png" alt="Billing"> Billing</a></label>
            <label><a href="Patient-profile.php"><img src="icons/patient/profile_icon.png" alt="Profile"> Profile</a></label>
            <label><a href="logout.php"><img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out</a></label>
        </div>

        <!-- this div where the right panel located and the other feature-->
        <div id="right_panel">
            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="message-p">
                <p>Good Day, <?php echo htmlspecialchars($firstname)?></p>
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
                <p>Search by</p>
                <label class="label">Provider</label>
                <label class="label">Specialization</label>
            </div>
        
            <div class="search-container">
                <div class="search-field">
                    <img src="icons/patient/search_icon.png" alt="Search Icon" class="search-icon" width="20" height="20">
                    <input type="text" class="search-input" placeholder="Search by doctor name or specialization">
                </div>
                <button class="search-button">Search</button>
            </div>

            <div class="browse-wrapper">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <a href="#" class="open-modal" 
                    data-id="<?php echo $row['DentistID']; ?>" 
                    data-firstname="<?php echo $row['Firstname']; ?>" 
                    data-middlename="<?php echo !empty($row['Middlename']) ? $row['Middlename'] : ''; ?>" 
                    data-lastname="<?php echo $row['Lastname']; ?>"
                    data-email="<?php echo $row['Email']; ?>"
                    data-img="<?php echo !empty($row['img']) ? $row['img'] : 'img/user_default.png'; ?>"
                    data-specialization="<?php echo $row['Specialization']; ?>"
                    data-description="<?php echo $row['Description']; ?>"
                    data-year-experience="<?php echo $row['YearExperience']; ?>"
                    data-monday="<?php echo !empty($row['Monday']) ? $row['Monday'] : 'Closed'; ?>"
                    data-tuesday="<?php echo !empty($row['Tuesday']) ? $row['Tuesday'] : 'Closed'; ?>"
                    data-wednesday="<?php echo !empty($row['Wednesday']) ? $row['Wednesday'] : 'Closed'; ?>"
                    data-thursday="<?php echo !empty($row['Thursday']) ? $row['Thursday'] : 'Closed'; ?>"
                    data-friday="<?php echo !empty($row['Friday']) ? $row['Friday'] : 'Closed'; ?>"
                    data-saturday="<?php echo !empty($row['Saturday']) ? $row['Saturday'] : 'Closed'; ?>"
                    data-sunday="<?php echo !empty($row['Sunday']) ? $row['Sunday'] : 'Closed'; ?>"
                >
                    <div class="browse-contaienr">
                    <img src="<?php echo !empty($row['img']) ? $row['img'] : 'img/user_default.png'; ?>" class="doctor-image">
                            <div class="browse-doctor-details">
                                <h2>Dr. <?php echo $row['Firstname'] . " " . (!empty($row['Middlename']) ? $row['Middlename'] . " " : "") . $row['Lastname']; ?></h2>
                                <p><?php echo $row['Specialization']; ?></p>
                            </div>         
                    </div>
                 </a>
                 <?php endwhile; ?>
            </div>
            
            <?php 
            // Close the database connection
            mysqli_close($connection);
            ?>
        </div>
        
        <!-- Doctor Modal--->
        <div id="doctorModal" class="modal-doctor" style="display: none;">
            <div class="modal-content-doctor">
                <span class="close-btn">×</span>
                
                <div class="doctor-header">
                    <img src="" alt="Doctor Image" class="doctor-image-content">
                    <div class="doctor-info">
                        <h2>Dr. </h2>
                        <p class="specialty"></p>
                        <button class="book-btn" onclick="openAppointmentModal()">BOOK AN APPOINTMENT</button>
                    </div>
                </div>

                <div class="doctor-description">
                    <p></p>
                </div>

                <div class="contact-section">
                    <h3>Contact Details</h3>
                    <div class="contact-info">
                        <i class="email-icon">✉</i>
                        <a href=""></a>
                    </div>
                </div>

                <div class="working-hours">
                    <h3>Working Hours</h3>
                    <div class="schedule-grid"></div>
                </div>
            </div>
        </div>

        <!-- Appointment Booking Modal -->
        <div id="appointmentModal" class="modal" style="display: none;">
            <div class="modal-content appointment-form">
                <div class="modal-header">
                    <button class="back-btn" onclick="backToDoctorModal()">
                        <span class="back-arrow">←</span> Back
                    </button>
                    <span class="close-btn" onclick="closeAppointmentModal()">×</span>
                </div>
                <form id="appointmentForm">
                    <div class="form-group">
                        <label for="doctorName">Doctor Name:</label>
                        <select id="chooseDentist" name="dentistID" required>
                            <option value="">Select Dentist</option>
                            <?php
                            if ($result2 && mysqli_num_rows($result2) > 0) {
                                while ($row = mysqli_fetch_assoc($result2)) {
                                    $dentistID = $row['DentistID'];
                                    $fullName = "Dr. " . $row['Firstname'] . " " . $row['Middlename'] . " " . $row['Lastname'];
                                    echo "<option value='$dentistID'>$fullName</option>";
                                }
                            } else {
                                echo "<option value=''>No Dentists Found</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Appointment Type -->
                    <div class="form-group">
                        <label for="appointmentType">Appointment Type:</label>
                        <select id="appointmentType" name="appointmentType" required onchange="showRelevantFields()">
                            <option value="">Select Type</option>
                            <?php foreach ($pricingData as $type => $subcategories): ?>
                                <option value="<?php echo htmlspecialchars(strtolower($type)); ?>">
                                    <?php echo htmlspecialchars($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Laboratory Type -->
                    <div class="form-group" id="appointmentLaboratoryGroup" style="display: none;">
                        <label for="appointmentLaboratory">Laboratory Type:</label>
                        <select id="appointmentLaboratory" name="appointmentLaboratory">
                            <option value="">Select Type</option>
                            <?php if (isset($pricingData['Laboratory'])): ?>
                                <?php foreach ($pricingData['Laboratory'] as $subCategory => $price): ?>
                                    <option value="<?php echo htmlspecialchars($subCategory); ?>">
                                        <?php echo htmlspecialchars($subCategory) . " - ₱" . number_format($price, 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Procedure Type -->
                    <div class="form-group" id="appointmentProcedureGroup" style="display: none;">
                        <label for="appointmentProcedure">Select Procedure:</label>
                        <select id="appointmentProcedure" name="appointmentProcedure">
                            <option value="">Select Type</option>
                            <?php if (isset($pricingData['Procedure'])): ?>
                                <?php foreach ($pricingData['Procedure'] as $subCategory => $price): ?>
                                    <option value="<?php echo htmlspecialchars($subCategory); ?>">
                                        <?php echo htmlspecialchars($subCategory) . " - ₱" . number_format($price, 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <!-- Treatment Type -->
                    <div class="form-group" id="appointmentTreatmentGroup" style="display: none;">
                        <label for="appointmentTreatment">Select Treatment:</label>
                        <select id="appointmentTreatment" name="appointmentTreatment">
                            <option value="">Select Type</option>
                            <?php if (isset($pricingData['Treatment'])): ?>
                                <?php foreach ($pricingData['Treatment'] as $subCategory => $price): ?>
                                    <option value="<?php echo htmlspecialchars($subCategory); ?>">
                                        <?php echo htmlspecialchars($subCategory) . " - ₱" . number_format($price, 2); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="appointmentDate">Date:</label>
                        <input type="date" id="appointmentDate" name="appointmentDate" required>
                    </div>

                    <div class="form-group">
                        <label for="timeStart">Time Start:</label>
                        <input type="time" id="timeStart" name="timeStart" required>
                    </div>

                    <div class="form-group">
                        <label for="timeEnd">Time End:</label>
                        <input type="time" id="timeEnd" name="timeEnd" required>
                    </div>

                    <div class="form-group">
                        <label for="paymentType">Payment Type:</label>
                        <select id="paymentType" name="paymentType" required>
                            <option value="">Select Payment</option>
                            <option value="cash">Cash-on-site</option>
                            <option value="card">Credit/Debit Card</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason for Appointment:</label>
                        <textarea id="reason" name="reason" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="book-btn">Book Appointment</button>
                </form>
            </div>
        </div>

        <script>
            // Get modal elements
            const modal = document.getElementById('doctorModal');
            const closeBtn = document.querySelector('.close-btn');
            const browseWrapper = document.querySelector('.browse-wrapper');
            
            // Open modal when clicking on browse wrapper
            browseWrapper.addEventListener('click', (e) => {
                e.preventDefault();
                modal.style.display = 'flex';
            });
            
            // Close modal when clicking close button
            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
            
            // Handle book appointment button click
            const bookBtn = document.querySelector('.book-btn');
            bookBtn.addEventListener('click', () => {
                console.log('Booking appointment...');
            });
        </script>

        <script>
            // Get modal elements
            const doctorModal = document.getElementById('doctorModal');
            const appointmentModal = document.getElementById('appointmentModal');
            const doctorCloseBtn = doctorModal.querySelector('.close-btn');
            
            // Open doctor modal when clicking on browse wrapper
            browseWrapper.addEventListener('click', (e) => {
                e.preventDefault();
                doctorModal.style.display = 'flex';
            });
            
            // Close doctor modal when clicking close button
            doctorCloseBtn.addEventListener('click', () => {
                doctorModal.style.display = 'none';
            });
            
            // Function to open appointment modal
            function openAppointmentModal() {
                doctorModal.style.display = 'none';
                appointmentModal.style.display = 'flex';
                
                // Set min date to today
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('appointmentDate').min = today;
            }
            
            // Function to close appointment modal
            function closeAppointmentModal() {
                appointmentModal.style.display = 'none';
                doctorModal.style.display = 'flex';
            }
            
            // Close modals when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === doctorModal) {
                    doctorModal.style.display = 'none';
                }
                if (e.target === appointmentModal) {
                    appointmentModal.style.display = 'none';
                }
            });
            
            // Validate time end is after time start
            document.getElementById('timeEnd').addEventListener('change', function() {
                const timeStart = document.getElementById('timeStart').value;
                const timeEnd = this.value;
                
                if (timeStart && timeEnd && timeEnd <= timeStart) {
                    alert('End time must be after start time');
                    this.value = '';
                }
            });

            function backToDoctorModal() {
                appointmentModal.style.display = 'none';
                doctorModal.style.display = 'flex';
            }
        </script>

        <!--script for display data from browse-wrapper to doctorModal-->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const modal = document.getElementById("doctorModal");
                const closeModal = document.querySelector(".close-btn");

                // Select all dentist links
                document.querySelectorAll(".open-modal").forEach(item => {
                    item.addEventListener("click", function (event) {
                        event.preventDefault(); // Prevent default link behavior

                        // Get dentist data from clicked element
                        const fullName = `Dr. ${this.dataset.firstname} ${this.dataset.middlename} ${this.dataset.lastname}`;
                        const imgSrc = this.dataset.img;
                        const specialization = this.dataset.specialization;
                        const description = this.dataset.description;
                        const email = this.dataset.email;
                        const experience = this.dataset.yearExperience;

                        // Populate modal
                        document.querySelector(".doctor-image-content").src = imgSrc;
                        document.querySelector(".doctor-info h2").innerText = fullName;
                        document.querySelector(".specialty").innerText = specialization;
                        document.querySelector(".doctor-description p").innerText = description;
                        document.querySelector(".contact-info a").innerText = email;
                        document.querySelector(".contact-info a").href = `mailto:${email}`;

                        // Set working hours dynamically
                        document.querySelector(".schedule-grid").innerHTML = `
                            <div class="schedule-item"><h4>MONDAY</h4><div class="time"><span>${this.dataset.monday}</span></div></div>
                            <div class="schedule-item"><h4>TUESDAY</h4><div class="time"><span>${this.dataset.tuesday}</span></div></div>
                            <div class="schedule-item"><h4>WEDNESDAY</h4><div class="time"><span>${this.dataset.wednesday}</span></div></div>
                            <div class="schedule-item"><h4>THURSDAY</h4><div class="time"><span>${this.dataset.thursday}</span></div></div>
                            <div class="schedule-item"><h4>FRIDAY</h4><div class="time"><span>${this.dataset.friday}</span></div></div>
                            <div class="schedule-item"><h4>SATURDAY</h4><div class="time"><span>${this.dataset.saturday}</span></div></div>
                            <div class="schedule-item"><h4>SUNDAY</h4><div class="time"><span>${this.dataset.sunday}</span></div></div>
                        `;

                        // Show modal
                        modal.style.display = "block";
                    });
                });

                // Close modal
                closeModal.addEventListener("click", function () {
                    modal.style.display = "none";
                });

                // Close modal when clicking outside
                window.addEventListener("click", function (event) {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                });
            });
        </script>

        <!--script for saving data in table of appointment-->
        <script>
            // Function to show relevant fields based on appointment type
            function showRelevantFields() {
                const appointmentType = document.getElementById("appointmentType").value.toLowerCase();

                document.getElementById("appointmentLaboratoryGroup").style.display = "none";
                document.getElementById("appointmentProcedureGroup").style.display = "none";
                document.getElementById("appointmentTreatmentGroup").style.display = "none";

                if (appointmentType === "laboratory") {
                    document.getElementById("appointmentLaboratoryGroup").style.display = "block";
                } else if (appointmentType === "procedure") {
                    document.getElementById("appointmentProcedureGroup").style.display = "block";
                } else if (appointmentType === "treatment") {
                    document.getElementById("appointmentTreatmentGroup").style.display = "block";
                }
            }

            document.getElementById('appointmentForm').addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent form from submitting traditionally

                // Validate 1-hour duration on client side
                const timeStart = document.getElementById('timeStart').value;
                const timeEnd = document.getElementById('timeEnd').value;

                const start = new Date(`1970-01-01T${timeStart}:00`);
                const end = new Date(`1970-01-01T${timeEnd}:00`);
                const diffMs = end - start;
                const diffHours = diffMs / (1000 * 60 * 60);

                if (diffHours > 1) {
                    alert("Appointments can only be booked for 1 hour. Please adjust your time selection.");
                    return;
                }

                if (diffHours <= 0) {
                    alert("End time must be after start time.");
                    return;
                }

                let formData = new FormData(this);

                // Debugging: Log form data to console
                for (let [key, value] of formData.entries()) {
                    console.log(key + ": " + value);
                }

                fetch('php/save_appointment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) // Get text instead of JSON first
                .then(text => {
                    console.log("Raw Response:", text); // Debugging output
                    try {
                        let data = JSON.parse(text); // Try parsing JSON
                        if (data.status === 'success') {
                            alert("Appointment booked successfully!");
                            closeAppointmentModal(); // Close modal
                            location.reload(); // Refresh page
                        } else {
                            alert("Error: " + data.message);
                        }
                    } catch (error) {
                        console.error("JSON Parse Error:", error, text);
                        alert("Unexpected server response. Check console for details.");
                    }
                })
                .catch(error => console.error('Fetch Error:', error));
            });
        </script>
    </body>
</html>