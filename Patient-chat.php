<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['PatientID'])) {
        header("Location: login.php");
        exit;
    }

    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Get dentist details
    $patient_id = $_SESSION['PatientID'];
    $dentist_id = mysqli_real_escape_string($connection, $_GET['dentist_id']);

    // Fetch dentist details
    $sql = "SELECT * FROM dentist WHERE DentistID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $dentist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $dentist = $result->fetch_assoc();


    if (!$dentist) {
        header("Location: Patient-message.php");
        exit;
    }

    // Fetch chat messages
    $chat_sql = "SELECT * FROM messages WHERE (incoming_msg_id = ? AND outgoing_msg_id = ?) OR (incoming_msg_id = ? AND outgoing_msg_id = ?) ORDER BY created_at ASC";
    $chat_stmt = $connection->prepare($chat_sql);
    $chat_stmt->bind_param("ssss", $dentist_id, $patient_id, $patient_id, $dentist_id);
    $chat_stmt->execute();
    $chat_result = $chat_stmt->get_result();

    $dentist_profile = !empty($dentist['img']) ? $dentist['img'] : 'img/user_default.png';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="css/patientstyle.css">
    <title>Appointment</title>
</head>
<body> 
    <!-- main div -->   
    <div id="wrapper">

        <!-- this the left panel located-->
        <div id="left_panel">
            <img id="logo" src="icons/patient/logo_seek4smiles.png" alt="Logo"> <!-- Add your logo image path -->
        
            <label>
                <a href="Patient-Homepage.php">
                    <img src="icons/patient/home_icon.png" alt="Home"> Homepage
                </a>
            </label>
            <label>
                <a href="Patient-notification.php">
                    <img src="icons/patient/notification_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Patient-record.php">
                    <img src="icons/patient/medical_record_icon.png" alt="Medical Records"> Medical Records
                </a>
            </label>
            <label>
                <a href="Patient-prescription.php">
                    <img src="icons/patient/prescription.png" alt="Medical Records"> Prescription Records
                </a>
            </label>
            <label>
                <a href="Patient-appointment.php">
                    <img src="icons/patient/calendar_icon.png" alt="Appointments"> Appointments
                </a>
            </label>
            <label>
                <a href="Patient-message.php">
                    <img src="icons/patient/message_icon.png" alt="Messages"> Messages
                </a>
            </label>
            <label>
                <a href="Patient-billing.php">
                    <img src="icons/patient/billing_icons.png" alt="Billing"> Billing
                </a>
            </label>
            <label>
                <a href="Patient-profile.php">
                    <img src="icons/patient/profile_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="index.php">
                    <img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <section class="chat-area">
                <header>
                    <img src="<?php echo htmlspecialchars($dentist_profile, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    <div class="details">
                        <span><?= htmlspecialchars($dentist['Firstname'].' '.$dentist['Lastname']) ?></span>
                        <p><?= htmlspecialchars($dentist['status']) ?></p>
                    </div>
                </header>
                <div class="chat-box">
                    <!-- <div class="chat outgoing">
                        <div class="details">
                            <p>Hi, Dr. Torillo! I have a quick question about the medication you prescribed.</p>
        
                        </div>
                    </div>
                    <div class="chat incoming">
                        <img src="icons/patient/Arnaldo.png" alt="">
                        <div class="details">
                            <p>Hi! Of course, what would you like to know?</p>
                        </div>
                    </div>
                    <div class="chat outgoing">
                        <div class="details">
                            <p>Is it normal to feel a little sensitivity in my gums after starting it?<br>11:30 AM</p>
                        </div>
                    </div>
                    <div class="chat incoming">
                        <img src="icons/patient/Arnaldo.png" alt="">
                        <div class="details">
                            <p>A little sensitivity is common, but it should go away in a day or two. If it persists, let me know.<br>11:30 AM</p>
                        </div>
                    </div> --> 
                    <?php if(mysqli_num_rows($chat_result) > 0): ?>
                        <?php while ($message = mysqli_fetch_assoc($chat_result)): ?>
                            <div class="chat <?= ($message['outgoing_msg_id'] == $patient_id) ? 'outgoing' : 'incoming' ?>">
                                <?php if ($message['outgoing_msg_id'] != $patient_id): ?>
                                    <img src="<?= htmlspecialchars($dentist['img'] ?? 'img/user_default.png') ?>" alt="">
                                <?php endif; ?>
                                <div class="details">
                                    <p><?= htmlspecialchars($message['msg']) ?></p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-messages">No messages yet. Start the conversation!</div>
                    <?php endif; ?>
                </div>
                <form id="chat-form" class="typing-area" method="POST">
                    <input type="text" name="outgoing_id" value="<?= htmlspecialchars($patient_id) ?>" hidden>
                    <input type="text" name="incoming_id" value="<?= htmlspecialchars($dentist_id) ?>" hidden>
                    
                    <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                    
                    <button type="submit" class="send-btn" name="send_btn" style="background-color: transparent; border: none;">
                        <img src="icons/patient/send.png" alt="Send message">
                    </button>
                </form>
            </section>
        </div>
    </div>
   <script>
        const form = document.querySelector(".typing-area");
    const sendBtn = form.querySelector(".send-btn");
    const inputField = form.querySelector(".input-field");
    const chatBox = document.querySelector(".chat-box");

    // Prevent default form submission
    form.onsubmit = (e) => e.preventDefault();

    // Enable send button when typing
    inputField.onkeyup = () => {
        sendBtn.classList.toggle("active", inputField.value.trim() !== "");
    };

    // Send message function
    sendBtn.onclick = () => {
        let formData = new FormData(form);

        fetch('php/insert_chat.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(() => {
            inputField.value = "";
            sendBtn.classList.remove("active");
            updateChat(); // Refresh chat messages
        })
        .catch(console.error);
    };

    // Fetch chat messages periodically
    function updateChat() {
        let incoming_id = document.querySelector("input[name='incoming_id']").value;
        let outgoing_id = document.querySelector("input[name='outgoing_id']").value;

        fetch('php/get_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `incoming_id=${incoming_id}&outgoing_id=${outgoing_id}`
        })
        .then(response => response.text())
        .then(data => {
            chatBox.innerHTML = data;
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(console.error);
    }

    // Update chat every 500ms
    setInterval(updateChat, 500);
    </script>
</body>
</html>
