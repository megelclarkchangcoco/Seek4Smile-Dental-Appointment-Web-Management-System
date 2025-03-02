<?php
session_start();
include 'connection.php';

$outgoing_id = $_POST['outgoing_id'];
$incoming_id = $_POST['incoming_id'];

$output = "";
$sql = "SELECT * FROM messages 
        LEFT JOIN patient ON patient.PatientID = messages.outgoing_msg_id
        LEFT JOIN dentist ON dentist.DentistID = messages.outgoing_msg_id
        WHERE (outgoing_msg_id = '$outgoing_id' AND incoming_msg_id = '$incoming_id')
        OR (outgoing_msg_id = '$incoming_id' AND incoming_msg_id = '$outgoing_id') 
        ORDER BY msg_id ASC";
$query = mysqli_query($connection, $sql);

if(mysqli_num_rows($query) > 0) {
    while($row = mysqli_fetch_assoc($query)) {
        $is_outgoing = $row['outgoing_msg_id'] == $outgoing_id;
        $name = $is_outgoing ? "You" : $row['Firstname'];
        $img = $is_outgoing 
            ? (!empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png')
            : (!empty($row['img']) ? $row['img'] : 'img/user_default.png');
        
        $output .= '<div class="chat '.($is_outgoing ? 'outgoing' : 'incoming').'">';
        if(!$is_outgoing) $output .= '<img src="'.$img.'" alt="">';
        $output .= '<div class="details">';
        if(!$is_outgoing) $output .= '<span class="name">'.$name.'</span>';
        $output .= '<p>'.htmlspecialchars($row['msg']).'</p>';
        $output .= '<span class="time">'.date('h:i A', strtotime($row['created_at'])).'</span>';
        $output .= '</div></div>';
    }
}
echo $output;
?>