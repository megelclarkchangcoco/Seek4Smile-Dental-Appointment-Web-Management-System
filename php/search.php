<?php
session_start();
include "connection.php";

if(isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);
    
    if(isset($_SESSION['PatientID'])) {
        // Search dentists
        $sql = "SELECT * FROM dentist 
                WHERE (Firstname LIKE '%{$searchTerm}%' 
                OR Lastname LIKE '%{$searchTerm}%')";
    } else {
        // Search patients
        $sql = "SELECT * FROM patient 
                WHERE (Firstname LIKE '%{$searchTerm}%' 
                OR Lastname LIKE '%{$searchTerm}%')";
    }
    
    $result = $conn->query($sql);
    include "user_list.php"; // Reuse the user list template
}
?>