<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'seek4smile_database');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Admin data (notice there are 8 fields + status)
$admins = [
    [
        'Karlos', 'Mendes', 'M', 45, '+1234567890',
        'kmendes@admin.com', 'admin_pass123', 'img/user_default.png'
    ],
    [
        'Anna', 'Delacruz', 'F', 38, '+9876543210',
        'anna.delacruz@admin.com', 'admin_pass456', 'img/Alyssa E. Navarro.png'
    ],
    [
        'Mark', 'Santiago', 'M', 41, '+7654321098',
        'mark.santiago@admin.com', 'admin_pass789', 'img/user_default.png'
    ]
];

// Prepare statement (8 placeholders + static 'Active' status)
$stmt = $mysqli->prepare("INSERT INTO `admin` 
    (Firstname, Lastname, Sex, Age, ContactDetails, Email, password, img, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Active')"); // 8 ? placeholders

foreach ($admins as $admin) {
    // Hash the password
    $hashedPassword = password_hash($admin[6], PASSWORD_DEFAULT);
    
    // Bind parameters (8 parameters total)
    $stmt->bind_param(
        'sssissss', // 8 type specifiers (NOT 9)
        $admin[0],  // Firstname (string)
        $admin[1],  // Lastname (string)
        $admin[2],  // Sex (string)
        $admin[3],  // Age (integer)
        $admin[4],  // ContactDetails (string)
        $admin[5],  // Email (string)
        $hashedPassword, // Hashed password (string)
        $admin[7]   // img (string)
    );
    
    if (!$stmt->execute()) {
        echo "Error inserting record: " . $stmt->error;
    }
}

$stmt->close();
$mysqli->close();
?>