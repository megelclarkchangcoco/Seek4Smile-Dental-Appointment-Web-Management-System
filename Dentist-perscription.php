<?php
include 'php/connection.php';
session_start();

if (!isset($_SESSION['DentistID'])) {
    header("Location: login.php");
    exit;
}

$dentist_id = $_SESSION['DentistID'];
$firstname = $_SESSION['Firstname'];
$lastname = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $global_notes = $_POST['global_notes'];  // Global notes only

    // Insert prescription data
    $stmt = $connection->prepare("INSERT INTO prescription (PatientID, PrescriptionDate, DentistID, Notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $patient_id, $date, $dentist_id, $global_notes);
    if (!$stmt->execute()) {
        echo "Error inserting prescription: " . $stmt->error;
    } else {
        $prescription_auto_id = $connection->insert_id;
        $formatted_prescription_id = 'PREID' . str_pad($prescription_auto_id, 4, '0', STR_PAD_LEFT);
        $connection->query("UPDATE prescription SET PrescriptionID = '$formatted_prescription_id' WHERE id = $prescription_auto_id");

        // Retrieve medicine arrays from form
        $medicines = $_POST['medicine'];
        $instructions = $_POST['instructions'];
        $refill_statuses = $_POST['refill_status'];
        $dosages = $_POST['dosage'];

        $stmt2 = $connection->prepare("INSERT INTO prescription_medicines (PrescriptionID, Medicine, Instructions, RefillStatus, Dosage) VALUES (?, ?, ?, ?, ?)");
        for ($i = 0; $i < count($medicines); $i++) {
            $medicine = $medicines[$i];
            $instr = $instructions[$i];
            $refill = $refill_statuses[$i];
            $dosage = $dosages[$i];

            $stmt2->bind_param("sssss", $formatted_prescription_id, $medicine, $instr, $refill, $dosage);
            if (!$stmt2->execute()) {
                echo "Error inserting medicine: " . $stmt2->error;
            } else {
                $medicine_auto_id = $connection->insert_id;
                $formatted_medicine_id = 'MEDID' . str_pad($medicine_auto_id, 3, '0', STR_PAD_LEFT);
                $connection->query("UPDATE prescription_medicines SET MedicineID = '$formatted_medicine_id' WHERE id = $medicine_auto_id");
            }
        }
        $stmt2->close();
        header("Location: " . $_SERVER['PHP_SELF'] . "?msg=success");
        exit;
    }
    $stmt->close();
}

// Fetch prescriptions for the logged-in dentist
$prescription_query = "SELECT * FROM prescription WHERE DentistID = '$dentist_id'";
$prescription_result = mysqli_query($connection, $prescription_query);

if(isset($_GET['msg']) && $_GET['msg'] === 'success'){
  echo "<script>alert('Prescription written successfully!');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
  <link rel="stylesheet" href="css/dentiststyle.css">
  <link rel="stylesheet" href="css/dentistmodal.css">
  <title>Prescription</title>
  <style>
    /* Reset some defaults */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Lato', sans-serif;
}

/* Main wrapper */
#wrapper {
    display: flex;
    min-height: 100vh;
}

/* Left panel (keeping as is, but with minor adjustments) */
#left_panel {
    width: 220px;
    background-color: #0085AA;
    padding: 20px 0;
    color: white;
    display: flex;
    flex-direction: column;
}

#logo {
    width: 80%;
    margin: 0 auto 30px;
    display: block;
}

#left_panel label {
    padding: 10px 20px;
    margin: 5px 0;
    cursor: pointer;
}

#left_panel label:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

#left_panel a {
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

#left_panel img {
    width: 20px;
    height: 20px;
}

/* Right panel */
#right_panel {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* Header section */
#header {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 10px 20px;
    background-color: white;
    border-bottom: 1px solid #eee;
}

#info {
    margin-right: 15px;
    text-align: right;
}

#fullname {
    font-weight: bold;
    margin-bottom: 3px;
}

#status {
    color: #777;
    font-size: 0.9em;
}

#profile_icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
  </style>
</head>
<body>
    ``<div id="wrapper">
      <!-- Left Panel -->
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
      <!-- Right Panel -->
      <div id="right_panel">
        <!-- Header -->
        <div id="header">
          <div id="info" style="text-align: left;">
            <p id="fullname">Dr. <?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
            <p id="status">Dentist</p>
          </div>
          <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
        </div>
        <div id="prescription-wrapper">
          <h1>Prescription</h1>
          <div class="prescription-search-wrapper">
            <div class="prescription-search-field">
              <img src="icons/dentist/search_icons.png" alt="Search Icon" class="prescription-search-icon" width="20" height="20">
              <input type="text" class="prescription-search-input" placeholder="Search by doctor name or specialization">
            </div>
            <button class="prescription-search-button">Search</button>
            <button class="add-prescription-button">
              <img src="icons/dentist/plus.png" alt="Add Icon" class="add-prescription-icon"> Add New
            </button>
          </div>
          <!-- Display Prescriptions -->
          <?php if (mysqli_num_rows($prescription_result) > 0): ?>
            <?php while ($prescription = mysqli_fetch_assoc($prescription_result)): ?>
              <div class="prescription-container">
                <div class="prescription-header">
                  <h2 class="medication-title">Prescription <?php echo htmlspecialchars($prescription['PrescriptionID']); ?></h2>
                  <p class="last-updated">Last Updated: <span><?php echo htmlspecialchars($prescription['created_at']); ?></span></p>
                </div>
                <div class="prescription-details">
                  <div class="column">
                    <p><strong>Patient:</strong> <?php echo htmlspecialchars($prescription['PatientID']); ?></p>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($prescription['PrescriptionDate']); ?></p>
                    <p><strong>Notes:</strong> <?php echo htmlspecialchars($prescription['Notes']); ?></p>
                    <p><strong>Doctor:</strong> Dr. <?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                  </div>
                  <div class="column">
                    <?php 
                      // Fetch only the first medicine entry for display
                      $pID = $prescription['PrescriptionID'];
                      $medicine_query = "SELECT * FROM prescription_medicines WHERE PrescriptionID = '$pID' LIMIT 1";
                      $medicine_result = mysqli_query($connection, $medicine_query);
                      if (mysqli_num_rows($medicine_result) > 0):
                        $medicine = mysqli_fetch_assoc($medicine_result);
                    ?>
                      <p>
                        <strong>Medicine:</strong> <?php echo htmlspecialchars($medicine['Medicine']); ?>,
                        <strong>Instructions:</strong> <?php echo htmlspecialchars($medicine['Instructions']); ?>,
                        <strong>Refill Status:</strong> <?php echo htmlspecialchars($medicine['RefillStatus']); ?>,
                        <strong>Dosage:</strong> <?php echo htmlspecialchars($medicine['Dosage']); ?>
                      </p>
                    <?php else: ?>
                      <p>No medicine entry found.</p>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="prescription-actions">
                  <!-- View button opens the PDF generator -->
                  <button class="action-button view" onclick="window.open('prescription_generate_pdf.php?prescription_id=<?php echo urlencode($prescription['PrescriptionID']); ?>', '_blank')" style="background-color: #006d8f; color: white;">View as PDF</button>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <p>No prescriptions found.</p>
          <?php endif; ?>
          
          <!-- Modal Overlay -->
          <div id="modalOverlay"></div>
          <!-- Modal for Adding Prescription -->
          <div class="modal-container" id="prescriptionModal">
            <div class="return-link" id="closeModal">
              <a href="#" class="back-arrow">←</a>
              <span>Return</span>
            </div>
            <h2 class="modal-title">Write a Prescription</h2>
            <form class="prescription-form" method="POST" action="">
              <div class="form-group">
                <label>Patient ID</label>
                <div class="select-wrapper">
                  <input type="text" name="patient_id" placeholder="Type patient id" class="form-input" required>
                  <span class="select-arrow"></span>
                </div>
              </div>
              <div class="form-group">
                <label>Date</label>
                <div class="date-input-wrapper">
                  <input type="date" name="date" placeholder="Date" class="form-input" required>
                  <span class="calendar-icon"></span>
                </div>
              </div>
              <div class="form-group">
                <label>Notes</label>
                <textarea name="global_notes" class="form-textarea" placeholder="Write overall notes"></textarea>
              </div>
              <h3 class="section-title">Prescription Details</h3>
              <div id="medicineContainer"></div>
              <button type="button" id="addMedicineBtn" class="add-medicine-button">Add Medicine</button>
              <div class="button-container">
                <button type="submit" class="save-button">SAVE</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <template id="medicineTemplate">
      <div class="medicine-group">
        <button type="button" class="remove-medicine">Remove</button>
        <h4>Medicine</h4>
        <div class="form-group">
          <label>Medicine</label>
          <div class="select-wrapper">
            <input type="text" name="medicine[]" placeholder="Select medicine" class="form-input" required>
            <span class="select-arrow"></span>
          </div>
        </div>
        <div class="form-group">
          <label>Instructions</label>
          <textarea name="instructions[]" class="form-textarea" placeholder="Write instructions"></textarea>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Refill Status</label>
            <div class="select-wrapper">
              <input type="text" name="refill_status[]" placeholder="Status" class="form-input" required>
              <span class="select-arrow"></span>
            </div>
          </div>
          <div class="form-group">
            <label>Dosage</label>
            <input type="text" name="dosage[]" placeholder="Quantity" class="form-input" required>
          </div>
        </div>
      </div>
    </template>``
  <script>
    const addButton = document.querySelector('.add-prescription-button');
    const modal = document.getElementById('prescriptionModal');
    const overlay = document.getElementById('modalOverlay');
    const closeButton = document.getElementById('closeModal');
    const addMedicineBtn = document.getElementById('addMedicineBtn');
    const medicineContainer = document.getElementById('medicineContainer');
    const medicineTemplate = document.getElementById('medicineTemplate').content;
    
    function openModal() {
      modal.classList.add('active');
      overlay.classList.add('active');
      document.body.classList.add('modal-open');
      if (medicineContainer.children.length === 0) {
        addMedicineGroup();
      }
    }
    
    function closeModal() {
      modal.classList.remove('active');
      overlay.classList.remove('active');
      document.body.classList.remove('modal-open');
    }
    
    function addMedicineGroup() {
      if (medicineContainer.children.length >= 3) {
        alert("You can only add a maximum of 3 medicine entries.");
        return;
      }
      const clone = document.importNode(medicineTemplate, true);
      clone.querySelector('.remove-medicine').addEventListener('click', function() {
        this.parentElement.remove();
      });
      medicineContainer.appendChild(clone);
    }
    
    addButton.addEventListener('click', openModal);
    closeButton.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape' && modal.classList.contains('active')) {
        closeModal();
      }
    });
    
    modal.addEventListener('click', function(event) {
      event.stopPropagation();
    });
    
    addMedicineBtn.addEventListener('click', addMedicineGroup);
  </script>
</body>
</html>