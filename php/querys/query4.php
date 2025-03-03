<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'seek4smile_database');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Dentist Assistant data (8 fields + status handled by query)
$assistants = [
    [
        'Jasmine', 'Ramos', 'F', 28, '09829831234',
        'jhramos@dentistassistant.com', 'assistantpass123', 'img/Jasmine H. Ramos.png'
    ],
    [
        'Mark', 'Salazar', 'M', 32, '09127831234',
        'msalazar@dentistassistant.com', 'markpass321', 'img/Mark P. Salazar.png'
    ],
    [
        'Linda', 'Cruz', 'F', 29, '09234567890',
        'lcruz@dentistassistant.com', 'lindapass456', 'img/Linda V. Cruz.png'
    ],
    [
        'Paul', 'Montoya', 'M', 35, '09345678901',
        'pmontoya@dentistassistant.com', 'paulpass789', 'img/Paul E. Montoya.png'
    ]
];

// Prepare statement (8 placeholders + static 'Offline' status)
$stmt = $mysqli->prepare("INSERT INTO `dentistassistant` 
    (Firstname, Lastname, Sex, Age, ContactDetails, Email, password, img, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Offline')");

foreach ($assistants as $assistant) {
    // Hash the password
    $hashedPassword = password_hash($assistant[6], PASSWORD_DEFAULT);

    
    // Bind parameters (8 parameters total)
    $stmt->bind_param(
        'sssissss', // Parameter types: 3 strings, 1 integer, 4 strings
        $assistant[0],  // Firstname
        $assistant[1],  // Lastname
        $assistant[2],  // Sex
        $assistant[3],  // Age (integer)
        $assistant[4],  // ContactDetails
        $assistant[5],  // Email
        $hashedPassword, // Hashed password
        $assistant[7]   // img
    );
    
    if (!$stmt->execute()) {
        echo "Error inserting record: " . $stmt->error;
    }
}

$stmt->close();
$mysqli->close();
?>