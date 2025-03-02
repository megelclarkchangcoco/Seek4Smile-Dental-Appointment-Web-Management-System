<?php
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientID = $_POST['PatientID'];
    $assistantID = $_POST['AssistantID'];
    $subject = mysqli_real_escape_string($connection, $_POST['subject']);
    
    // Handle File Upload
    $targetDir = "uploads/"; // Directory where files are stored
    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow only specific file types
    $allowedTypes = array("pdf", "docx", "jpg", "png");
    if (!in_array($fileType, $allowedTypes)) {
        echo "Invalid file type. Allowed types: PDF, DOCX, JPG, PNG.";
        exit();
    }

    // Move uploaded file to server folder
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        // Insert into database
        $query = "INSERT INTO medicalrecord (PatientID, AssistantID, subject, filename) 
                  VALUES ('$patientID', '$assistantID', '$subject', '$targetFilePath')";
        if (mysqli_query($connection, $query)) {
            echo "File uploaded successfully!";
            header("Location: Assistant-patients.php?view=" . $patientID);
        } else {
            echo "Database error: " . mysqli_error($connection);
        }
    } else {
        echo "Error uploading file.";
    }
}

// Close connection
mysqli_close($connection);
?>
