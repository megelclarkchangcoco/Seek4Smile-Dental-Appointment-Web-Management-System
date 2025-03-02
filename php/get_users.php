<?php 
session_start();
include "connection.php";

if(isset($_SESSION['PatientID'])) {
    // Get online dentists for patient
    $sql = "SELECT DentistID, Firstname, Lastname, img, status FROM dentist WHERE status = 'Online'";
    $result = $connection->query($sql);
    
    $output = "";
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Get last message
            $last_msg_sql = "SELECT msg FROM messages 
                            WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) 
                            OR (incoming_msg_id = ? AND outgoing_msg_id = ?) 
                            ORDER BY msg_id DESC LIMIT 1";
            $stmt = $connection->prepare($last_msg_sql);
            $stmt->bind_param("ssss", $row['DentistID'], $_SESSION['PatientID'], 
                            $_SESSION['PatientID'], $row['DentistID']);
            $stmt->execute();
            $last_msg = $stmt->get_result()->fetch_assoc();
            
            $msg = $last_msg ? htmlspecialchars(substr($last_msg['msg'], 0, 28)) : "Start a conversation...";
            
            $output .= '<a href="Patient-chat.php?dentist_id='.$row['DentistID'].'">
                <div class="message-container">
                    <img src="'.$row['img'].'" class="doctor-image">
                    <div class="doctor-details">
                        <h2>Dr. '.$row['Firstname'].' '.$row['Lastname'].'</h2>
                        <p>'.$msg.'</p>
                    </div>
                    <div class="status-dot '.($row['status'] == 'Online' ? 'online' : '').'"></div>
                </div>
            </a>';
        }
    }
    echo $output;
}

?>