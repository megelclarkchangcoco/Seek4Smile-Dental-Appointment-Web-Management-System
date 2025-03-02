<?php 
    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['DentistID'])) {
        header("Location: login.php");
        exit;
    }

    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';
    $dentist_id = $_SESSION['DentistID'];
    $dentist_name = $_SESSION['Firstname'] . ' ' . $_SESSION['Lastname'];
    
    // Get patient details from URL parameter
    if (!isset($_GET['patient_id'])) {
        header("Location: Dentist-message.php");
        exit;
    }
    
    $patient_id = mysqli_real_escape_string($connection, $_GET['patient_id']);

    // Validate patient exists
    $stmt = $connection->prepare("SELECT * FROM patient WHERE PatientID = ?");
    $stmt->bind_param("s", $patient_id);
    $stmt->execute();
    $patient = $stmt->get_result()->fetch_assoc();
    
    $patient_profile = !empty($patient['img']) ? $patient['img'] : 'img/user_default.png';

    if (!$patient) {
        header("Location: Dentist-message.php");
        exit;
    }
    
    // Fetch chat history
    $chat_query = "SELECT * FROM messages 
                  LEFT JOIN patient ON patient.PatientID = messages.outgoing_msg_id
                  LEFT JOIN dentist ON dentist.DentistID = messages.outgoing_msg_id
                  WHERE (outgoing_msg_id = '$dentist_id' AND incoming_msg_id = '$patient_id')
                  OR (outgoing_msg_id = '$patient_id' AND incoming_msg_id = '$dentist_id')
                  ORDER BY msg_id ASC";
    $chat_result = mysqli_query($connection, $chat_query);
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
            <img id="logo" src="icons/patient/seek4smilesLogo.png" alt="Logo"> <!-- Add your logo image path -->
        
            <label>
                <a href="Dentist-homepage.php">
                    <img src="icons/dentalassistant/home_icon.png" alt="Dashboard"> Dashboard
                </a>
            </label>
            <label>
                <a href="Dentist-notification.php">
                    <img src="icons/dentalassistant/notif_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Dentist-patient.php">
                    <img src="icons/dentalassistant/patient_icon.png" alt="Patients"> Patients
                </a>
            </label>
            <label>
                <a href="Dentist-perscription.php">
                    <img src="icons/patient/prescription.png" alt="Prescription"> Prescription
                </a>
            </label>
            <label>
                <a href="Dentist-appointment.php">
                    <img src="icons/dentist/calendar_icon.png" alt="Calendar"> Calendar
                </a> 
            </label>
            <label>
                <a href="Dentist-message.php">
                    <img src="icons/patient/message_icon.png" alt="Messages"> Messages
                </a> 
            </label>
            <label>
                <a href="Dentist-profile.php">
                    <img src="icons/dentist/profile_icon.png" alt="Profile"> Profile
                </a> 
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/dentalassistant/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname">Dr. <?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Dentist</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <section class="chat-area">
                <header>
                    <img src="<?php echo htmlspecialchars($patient_profile, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                    <div class="details">
                        <span><?= htmlspecialchars($patient['Firstname'] . ' ' . $patient['Lastname']) ?></span>
                        <p><?= htmlspecialchars($patient['status']) ?></p>
                    </div>
                </header>
                <!-- <div class="chat-box">
                    <div class="chat outgoing">
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
                    </div>
                </div> -->
                <div class="chat-box">
                    <?php if(mysqli_num_rows($chat_result) > 0): ?>
                        <?php while($message = mysqli_fetch_assoc($chat_result)): ?>
                            <div class="chat <?= ($message['outgoing_msg_id'] == $dentist_id) ? 'outgoing' : 'incoming' ?>">
                                <?php if($message['outgoing_msg_id'] != $dentist_id): ?>
                                    <img src="<?= htmlspecialchars($patient['img'] ?? 'img/user_default.png') ?>" alt="">
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
                <form id="chat-form" class="typing-area" method="POST" enctype="multipart/form-data">
                    <input type="text" name="outgoing_id" value="<?= htmlspecialchars($dentist_id) ?>" hidden>
                    <input type="text" name="incoming_id" value="<?= htmlspecialchars($patient_id) ?>" hidden>
                    
                    <button type="button" class="image-upload" style="background-color: transparent; border: none;">
                        <img src="icons/patient/ping.png" alt="Attach file">
                        <input type="file" name="send_image" accept="image/*" hidden>
                    </button>
                    
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
