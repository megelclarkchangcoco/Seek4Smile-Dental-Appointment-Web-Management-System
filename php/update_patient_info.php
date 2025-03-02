<?php
include 'php/connection.php';
session_start();

// Set JSON response header
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['PatientID'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientID = $_SESSION['PatientID'];

    // Get updated values from form submission
    $firstname = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $age = $_POST['age'] ?? '';
    $houseNumberStreet = $_POST['houseNumberStreet'] ?? '';
    $barangay = $_POST['barangay'] ?? '';
    $cityMunicipality = $_POST['cityMunicipality'] ?? '';

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($sex) || empty($age)) {
        echo json_encode(["status" => "error", "message" => "Required fields cannot be empty."]);
        exit;
    }

    // Update patient information
    $query = "UPDATE patient SET 
        Firstname = ?, 
        Middlename = ?, 
        Lastname = ?, 
        Sex = ?, 
        Age = ?, 
        HouseNumberStreet = ?, 
        Barangay = ?, 
        CityMunicipality = ?
        WHERE PatientID = ?";

    if ($stmt = $connection->prepare($query)) {
        $stmt->bind_param("ssssissss", 
            $firstname, 
            $middlename, 
            $lastname, 
            $sex, 
            $age, 
            $houseNumberStreet, 
            $barangay, 
            $cityMunicipality, 
            $patientID
        );

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Information updated successfully!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database update failed: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "SQL query preparation failed."]);
    }

    $connection->close();
}
?>
