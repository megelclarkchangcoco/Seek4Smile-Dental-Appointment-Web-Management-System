<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['AdminID'])) {
        header("Location: login.php");
        exit;
    }

    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <title>Admin</title>
</head>
<body>
    <div class="profile">
            <img src="<?= $profile_img ?>" alt="Admin Profile">
            <h2>Welcome, <?= $firstname ?> <?= $lastname ?> (Admin)</h2>
    </div>

    <a href="logout.php">
        <img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out
    </a>
</body>
</html>