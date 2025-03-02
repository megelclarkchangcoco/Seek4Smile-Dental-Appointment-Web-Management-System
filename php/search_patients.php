<?php
include 'connection.php';
session_start();

if (!isset($_SESSION['DentistID'])) {
    die("Unauthorized access");
}

$searchTerm = mysqli_real_escape_string($connection, $_POST['searchTerm']);
$dentist_id = $_SESSION['DentistID'];

$sql = "SELECT p.*, 
        (SELECT msg FROM messages 
         WHERE (outgoing_msg_id = '$dentist_id' AND incoming_msg_id = p.PatientID)
         OR (outgoing_msg_id = p.PatientID AND incoming_msg_id = '$dentist_id') 
         ORDER BY msg_id DESC LIMIT 1) AS last_message
        FROM patient p 
        WHERE p.status = 'Online'
        AND (p.Firstname LIKE '%$searchTerm%' 
             OR p.Lastname LIKE '%$searchTerm%'
             OR CONCAT(p.Firstname, ' ', p.Lastname) LIKE '%$searchTerm%')";

$result = mysqli_query($connection, $sql);
$output = "";

if(mysqli_num_rows($result) > 0) {
    while($patient = mysqli_fetch_assoc($result)) {
        $output .= '
        <a href="Dentist-chat.php?patient_id='.$patient['PatientID'].'">
            <div class="message-container">
                <img src="'.(!empty($patient['img']) ? $patient['img'] : 'img/user_default.png').'" 
                     class="user-image" 
                     alt="'.$patient['Firstname'].'">
                <div class="user-details">
                    <h2>'.htmlspecialchars($patient['Firstname'].' '.$patient['Lastname']).'</h2>
                    <p class="last-message">'
                        .(!empty($patient['last_message']) 
                            ? htmlspecialchars($patient['last_message']) 
                            : 'No messages yet').'
                    </p>
                </div>
                <div class="status-dot '.($patient['status'] === 'Online' ? 'online' : '').'"></div>
            </div>
        </a>';
    }
} else {
    $output = '<div class="no-users">No patients found matching "'.htmlspecialchars($searchTerm).'"</div>';
}

echo $output;
?>