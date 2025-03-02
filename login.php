<?php
include 'php/connection.php';
session_start();

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    
    // Validate email format and extract domain
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $alert[] = "Invalid email format!";
    } else {
        $domain = strtolower(explode('@', $email)[1]);
        $userType = '';
        $table = '';
        $idField = '';

        // Domain-to-UserType mapping
        switch ($domain) {
            case 'admin.com':
                $userType = 'Admin';
                $table = 'admin';
                $idField = 'AdminID';
                break;
            case 'billingspecialist.com':
                $userType = 'BillingSpecialist';
                $table = 'billingspecialist';
                $idField = 'SpecialistID';
                break;
            case 'dentist.com':
                $userType = 'Dentist';
                $table = 'dentist';
                $idField = 'DentistID';
                break;
            case 'dentistassistant.com':
                $userType = 'Assistant';
                $table = 'dentistassistant';
                $idField = 'AssistantID';
                break;
            case 'gmail.com':
                $userType = 'Patient';
                $table = 'patient';
                $idField = 'PatientID';
                break;
            default:
                $alert[] = "Invalid email domain!";
                break;
        }

        if ($userType && $table) {
            // Case-insensitive email comparison
            $query = "SELECT * FROM `$table` WHERE LOWER(Email) = LOWER('$email')";
            $result = mysqli_query($connection, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                
                if (password_verify($password, $row['password'])) {
                    // Session setup
                    $_SESSION[$idField] = $row[$idField];
                    $_SESSION['Firstname'] = $row['Firstname'];
                    $_SESSION['Lastname'] = $row['Lastname'];
                    $_SESSION['UserType'] = $userType;
                    $_SESSION['img'] = $row['img'] ?? '';

                    // Update status
                    mysqli_query($connection, "UPDATE `$table` SET status='Online' WHERE $idField='{$row[$idField]}'");

                    // Log activity
                    mysqli_query($connection, "INSERT INTO activity_log (UserType, UserID, Activity)
                                      VALUES ('$userType', '{$row[$idField]}', 'Login')");

                    // Redirect
                    header("Location: {$userType}-Homepage.php");
                    exit();
                } else {
                    $alert[] = "Invalid password!";
                }
            } else {
                $alert[] = "No account found with this email!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="css/login.css"> <!-- Link your CSS file -->
    <title>Login Page</title>
</head>
<body>
    <div class="header">
        <a href="index.php"><img src="icons/patient/seek4smilesLogo.png" alt=""></a>
    </div>

    <div class="container">
        <div class="background-overlay" style="background-image: url('icons/patient/background.png');"></div>

         <!-- Form Container -->
         <div class="form-container">
            <!-- Form -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="tabs">
                    <a href="#" class="active">SIGN IN</a>
                    <a href="register.php">SIGN UP</a>
                </div>

                <h3>Sign in to your account</h3>
                <p class="subtitle">Book an appointment and access medical records, anytime, anywhere.</p>
                
                <div class="input-group">
                    <?php 
                        if (!empty($alert)) {
                            foreach ($alert as $err) {
                                echo "<h3 class=\"alert\">$err</h3>";
                            }
                        }
                    ?>
                    <!-- Email and Password -->
                    <input type="email" name="email" placeholder="Email" class="box" required>
                    <input type="password" name="password" placeholder="Password" class="box" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="login" class="btn">Submit</button>
                <!-- Footer Links -->   
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Use</a>
                </div>
            </form>
        </div>   

        </div>
    </div>
</body>
</html>