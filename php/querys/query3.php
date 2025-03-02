<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'seek4smile_database');

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Dentist data (11 fields + status)
$dentists = [
    [
        'Arnaldo', 'Turillo', 'M', 40, '09123456789',
        'arnaldo.turillo@dentist.com', 'dentist_pass123', 'img/Dr. Arnaldo A. Turillo.png',
        'Oral Surgery', 'Specialist in wisdom tooth extractions.', '15 years'
    ],
    [
        'Mia', 'Alvarez', 'F', 38, '09378881234',
        'mia.alvarez@dentist.com', 'dentist_pass123', 'img/Dr. Mia R. Alvarez.png',
        'General Dentistry', 'Experienced in fillings and extractions.', '12 years'
    ],
    [
        'Sophia', 'Reyes', 'F', 42, '09365509123',
        'sophia.reyes@dentist.com', 'dentist_pass123', 'img/Dr. Sophia L. Reyes.png',
        'Orthodontics', 'Specialist in braces and alignments.', '18 years'
    ],
    [
        'Isabella', 'Torres', 'F', 36, '09795550123',
        'isabelle.torres@dentist.com', 'dentist_pass123', 'img/Dr. Isabella T. Torres.png',
        'Implants', 'Focuses on dental implants.', '10 years'
    ],
    [
        'Lucas', 'Ramirez', 'M', 44, '09395550345',
        'lucas.ramirez@dentist.com', 'dentist_pass123', 'img/Dr. Lucas M. Ramirez.png',
        'Endodontics', 'Root canal treatments.', '15 years'
    ],
    [
        'Olivia', 'Castillo', 'F', 30, '09385550123',
        'olivia.castillo@dentist.com', 'dentist_pass123', 'img/Dr. Olivia K. Castillo.png',
        'Cosmetic Dentistry', 'Specializes in veneers and whitening.', '8 years'
    ],
    [
        'Evan', 'Santos', 'M', 39, '09835550123',
        'evan.santos@dentist.com', 'dentist_pass123', 'img/Dr. Evan J. Santos.png',
        'General Dentistry', 'Experienced in cleanings and exams.', '13 years'
    ],
    [
        'Daniel', 'Martinez', 'M', 46, '09375550123',
        'daniel.martinez@dentist.com', 'dentist_pass123', 'img/Daniel P. Martinez.png',
        'Periodontics', 'Specialist in gum diseases.', '20 years'
    ]
];

// Prepare statement (11 placeholders + static 'Offline' status)
$stmt = $mysqli->prepare("INSERT INTO `dentist` 
    (Firstname, Lastname, Sex, Age, ContactDetails, Email, password, img, Specialization, Description, YearExperience, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Offline')");

foreach ($dentists as $dentist) {
    // Hash the password
    $hashedPassword = password_hash($dentist[6], PASSWORD_DEFAULT);
    
    // Corrected type string and parameter order
    $stmt->bind_param(
        'sssisssssss', // Corrected type specification
        $dentist[0],   // Firstname (string)
        $dentist[1],   // Lastname (string)
        $dentist[2],   // Sex (string)
        $dentist[3],   // Age (integer)
        $dentist[4],   // ContactDetails (string)
        $dentist[5],   // Email (string)
        $hashedPassword, // Hashed password (string)
        $dentist[7],   // img (string)
        $dentist[8],   // Specialization (string)
        $dentist[9],   // Description (string)
        $dentist[10]   // YearExperience (string)
    );
    
    if (!$stmt->execute()) {
        echo "Error inserting record: " . $stmt->error;
    }
}

$stmt->close();
$mysqli->close();
?>