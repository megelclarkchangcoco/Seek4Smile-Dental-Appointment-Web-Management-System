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

$patient_id = $_SESSION['PatientID'];

// Check if search is set
$searchQuery = "";
$searchCondition = "";
$searchParams = [];

if (isset($_POST['search']) && !empty($_POST['search'])) {
    $searchQuery = trim($_POST['search']);
    $searchCondition = " AND (d.Firstname LIKE ? OR d.Middlename LIKE ? OR d.Lastname LIKE ? 
                             OR CONCAT(d.Firstname, ' ', d.Lastname) LIKE ? 
                             OR d.Specialization LIKE ?)";
    $searchParam = "%" . $searchQuery . "%";
    $searchParams = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
}

// Fetch dentists (including search filter)
$sql = "SELECT d.*, 
        (SELECT msg FROM messages 
        WHERE (outgoing_msg_id = ? AND incoming_msg_id = d.DentistID) 
            OR (outgoing_msg_id = d.DentistID AND incoming_msg_id = ?) 
        ORDER BY msg_id DESC LIMIT 1) AS last_message,
        (SELECT created_at FROM messages 
        WHERE (outgoing_msg_id = ? AND incoming_msg_id = d.DentistID) 
            OR (outgoing_msg_id = d.DentistID AND incoming_msg_id = ?) 
        ORDER BY msg_id DESC LIMIT 1) AS last_message_time
        FROM dentist d 
        WHERE (d.status = 'Online' 
        OR EXISTS (
            SELECT 1 FROM messages 
            WHERE (outgoing_msg_id = ? AND incoming_msg_id = d.DentistID) 
                OR (outgoing_msg_id = d.DentistID AND incoming_msg_id = ?)
        )) 
        $searchCondition
        ORDER BY d.status DESC, last_message_time DESC";

$stmt = $connection->prepare($sql);

// Bind parameters dynamically
if (!empty($searchQuery)) {
    $stmt->bind_param("ssssss" . str_repeat("s", count($searchParams)), 
                      $patient_id, $patient_id, $patient_id, $patient_id, $patient_id, $patient_id, 
                      ...$searchParams);
} else {
    $stmt->bind_param("ssssss", $patient_id, $patient_id, $patient_id, $patient_id, $patient_id, $patient_id);
}

$stmt->execute();
$result = $stmt->get_result();
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
    <div id="wrapper">

        <div id="left_panel">
            <img id="logo" src="icons/patient/logo_seek4smiles.png" alt="Logo">
        
            <label><a href="Patient-Homepage.php"><img src="icons/patient/home_icon.png"> Homepage</a></label>
            <label><a href="Patient-notification.php"><img src="icons/patient/notification_icon.png"> Notifications</a></label>
            <label><a href="Patient-record.php"><img src="icons/patient/medical_record_icon.png"> Medical Records</a></label>
            <label><a href="Patient-prescription.php"><img src="icons/patient/prescription.png"> Prescription Records</a></label>
            <label><a href="Patient-appointment.php"><img src="icons/patient/calendar_icon.png"> Appointments</a></label>
            <label><a href="Patient-message.php"><img src="icons/patient/message_icon.png"> Messages</a></label>
            <label><a href="Patient-billing.php"><img src="icons/patient/billing_icons.png"> Billing</a></label>
            <label><a href="Patient-profile.php"><img src="icons/patient/profile_icon.png"> Profile</a></label>
            <label><a href="logout.php"><img src="icons/patient/signout_icon.png"> Sign Out</a></label>
        </div>

        <div id="right_panel">
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="message-p">
                <p>Messages</p>
            </div>

            <form method="POST">
                <div class="message-search-wrapper">
                    <div class="message-search-field">
                        <img src="icons/patient/search_icon.png" alt="Search Icon" class="message-search-icon" width="20" height="20">
                        <input type="text" name="search" class="message-search-input" placeholder="Search by doctor name or specialization"
                            value="<?php echo htmlspecialchars($searchQuery); ?>">
                    </div>
                    <button type="submit" class="message-search-button">Search</button>        
                </div>
            </form>

            <div class="message-wrapper">
            <?php while($dentist = $result->fetch_assoc()): ?>
                <a href="patient-chat.php?dentist_id=<?= htmlspecialchars($dentist['DentistID']) ?>">
                    <div class="message-contaienr">
                        <img src="<?= !empty($dentist['img']) ? htmlspecialchars($dentist['img']) : 'img/user_default.png' ?>" class="doctor-image">
                        <div class="doctor-details">
                            <h2>Dr. <?= htmlspecialchars($dentist['Firstname'].' '.$dentist['Lastname']) ?></h2>
                            <p><?= htmlspecialchars($dentist['last_message'] ?? 'No messages yet') ?></p>
                        </div>
                        <div class="status-dot <?= $dentist['status'] === 'Online' ? 'online' : '' ?>"></div>
                    </div>
                </a>
            <?php endwhile; ?>
            </div>
        </div>
    </div>

</body>
</html>
