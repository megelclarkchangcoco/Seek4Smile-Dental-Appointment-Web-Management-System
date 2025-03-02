<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'seek4smile_database');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Billing Specialist data (8 fields + status handled by trigger)
$specialists = [
    [
        'Lara Janine', 'Alcantara', 'F', 32, '09987654321',
        'larajaninealcantara@billingspecialist.com', 'billing_pass123', 'img/Lara Janine Alcantara.png'
    ],
    // Add more specialists as needed
    [
        'John', 'Doe', 'M', 28, '+639123456789',
        'john.doe@billingspecialist.com', 'billing_pass456', ''
    ],
    [
        'Maria', 'Clara', 'F', 35, '+639987654321',
        'maria.clara@billingspecialist.com', 'billing_pass789', ''
    ]
];

// Prepare statement (8 placeholders + static 'Active' status)
$stmt = $mysqli->prepare("INSERT INTO `billingspecialist` 
    (Firstname, Lastname, Sex, Age, ContactDetails, Email, password, img, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')");

foreach ($specialists as $specialist) {
    // Hash the password
    $hashedPassword = password_hash($specialist[6], PASSWORD_DEFAULT);
    
    // Bind parameters (8 parameters total)
    $stmt->bind_param(
        'sssissss', // Parameter types: 3 strings, 1 integer, 4 strings
        $specialist[0],  // Firstname
        $specialist[1],  // Lastname
        $specialist[2],  // Sex
        $specialist[3],  // Age
        $specialist[4],  // ContactDetails
        $specialist[5],  // Email
        $hashedPassword, // Hashed password
        $specialist[7]   // img
    );
    
    if (!$stmt->execute()) {
        echo "Error inserting record: " . $stmt->error;
    }
}

$stmt->close();
$mysqli->close();
?>