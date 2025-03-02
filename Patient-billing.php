<?php 
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['PatientID'])) {
        header("Location: login.php");
        exit;
    }

    $patientID = $_SESSION['PatientID'];
    $firstname = $_SESSION['Firstname']; 
    $lastname = $_SESSION['Lastname'];  
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png'; 
    
    //  Correct Outstanding Balance Calculation (Fix Installments)
    $queryBalance = "SELECT ab.BillingID, ab.TotalFee, 
                    (SELECT COALESCE(SUM(p.PaymentAmount), 0) FROM payments p WHERE p.BillingID = ab.BillingID) AS TotalPaid
                    FROM appointmentbilling ab
                    WHERE ab.PatientID = ? 
                    AND ab.PaymentStatus IN ('unpaid', 'partial')";

    $stmtBalance = mysqli_prepare($connection, $queryBalance);
    mysqli_stmt_bind_param($stmtBalance, "s", $patientID);
    mysqli_stmt_execute($stmtBalance);
    $resultBalance = mysqli_stmt_get_result($stmtBalance);

    $totalBalance = 0;
    while ($row = mysqli_fetch_assoc($resultBalance)) {
        $remainingBalance = max($row['TotalFee'] - $row['TotalPaid'], 0); // Prevent negative balance
        $totalBalance += $remainingBalance;
    }

    mysqli_stmt_close($stmtBalance);

    //  Query to get only unpaid penalty appointments
    $queryPenalty = "SELECT COUNT(*) AS unpaidPenaltyCount FROM appointment a
                     JOIN appointmentbilling ab ON a.AppointmentID = ab.AppointmentID
                     WHERE a.PatientID = ? 
                     AND a.AppointmentStatus = 'penalty'
                     AND ab.PaymentStatus = 'unpaid'";

    $stmtPenalty = mysqli_prepare($connection, $queryPenalty);
    mysqli_stmt_bind_param($stmtPenalty, "s", $patientID);
    mysqli_stmt_execute($stmtPenalty);
    mysqli_stmt_bind_result($stmtPenalty, $unpaidPenaltyCount);
    mysqli_stmt_fetch($stmtPenalty);
    mysqli_stmt_close($stmtPenalty);

    //  Calculate total penalty fee (Only unpaid ones)
    $penaltyFee = $unpaidPenaltyCount * 2000;

    //  Final Total Outstanding Balance
    $totalBalance += $penaltyFee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <link rel="stylesheet" href="css/patientstyle.css">
    <title>Browse</title>

</head>
<body>    
    <div id="wrapper">
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
                <a href="logout.php">
                    <img src="icons/patient/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <div id="right_panel">
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?php echo $firstname . ' ' . $lastname; ?></p>
                    <p id="status">Patient</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8');?>" alt="Profile Icon">
            </div>

            <div class="payment-container">
                
                <div class="payment-dashboard">
                    <div class="balance-container"> 
                        <div class="balance-header">Outstanding Balance</div>
                        <div class="balance-amount">₱<?php echo number_format($totalBalance, 2); ?></div>
                    </div>
            
                    <div class="add-credit-card" onclick="openModal()">
                        <h3>Add Credit Card</h3>
                        <p>Click here to add a new payment method</p>
                    </div>
                </div>
                
                <!-- Modal Section -->
                <div class="card-modal" id="cardModal">
                    <div class="modal-content">
                        <span class="close-modal" onclick="closeModal()">&times;</span>
                        <form id="cardForm" onsubmit="handleSubmit(event)">
                            <div class="col">
                                <h3 class="title" style="text-align: center;">Payment</h3>
                
                                <!-- Card Type Selection -->
                                <div class="inputBox">
                                    <label for="cardType">Select Card Type:</label>
                                    <div class="card-options">
                                        <input type="radio" id="visa" name="cardType" value="Visa">
                                        <label for="visa">
                                            <img src="icons/patient/visa_icon.jpg" alt="Visa">
                                        </label>
                                
                                        <input type="radio" id="mastercard" name="cardType" value="Mastercard">
                                        <label for="mastercard">
                                            <img src="icons/patient/mastercard_icon.png" alt="Mastercard">
                                        </label>
                                
                                        <!-- BDO Card -->
                                        <input type="radio" id="bdo" name="cardType" value="BDO">
                                        <label for="bdo">
                                            <img src="icons/patient/bdo_icon.jpg" alt="BDO">
                                        </label>
                                
                                        <!-- BPI Card -->
                                        <input type="radio" id="bpi" name="cardType" value="BPI">
                                        <label for="bpi">
                                            <img src="icons/patient/bpi_icon.jpg" alt="BPI">
                                        </label>
                                    </div>
                                </div>
                                
                
                                <!-- Name on Card -->
                                <div class="inputBox">
                                    <label for="cardName">Name On Card:</label>
                                    <input type="text" id="cardName" placeholder="Enter card name" required>
                                </div>
                
                                <!-- Credit Card Number -->
                                <div class="inputBox">
                                    <label for="cardNum">Credit Card Number:</label>
                                    <input type="text" id="cardNum" placeholder="1111-2222-3333-4444" maxlength="19" oninput="formatCardNumber(this)" required>
                                </div>
                                
                                <!-- Expiry & CVV -->
                                <div class="flex">
                                    <div class="inputBox">
                                        <label for="expiry">Expiry Date:</label>
                                        <input type="month" id="expiry" required>
                                    </div>                                    
                                    <div class="inputBox">
                                        <label for="cvv">CVV:</label>
                                        <input type="password" id="cvv" placeholder="•••" maxlength="4" pattern="\d{3,4}" title="Enter a 3 or 4-digit CVV" oninput="validateNumeric(this)" required>
                                    </div>
                                </div>
                
                                <!-- Centered and smaller button -->
                                <button type="submit" class="submit-btn">Add Card</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div id="overview-content" class="tab-content active">
                    <!-- Billing History Table -->
                    <div class="billing-section">
                        <h2>Invoice Billing History</h2>
                        <table class="billing-table">
                        <thead>
                            <tr>
                                <th>BillingID</th>
                                <th>AppointmentID</th>
                                <th>Treatment Name</th>
                                <th>Type</th>
                                <th>Treatment Status</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Cost</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                            <tbody>
                            <?php
                                if (!$connection) {
                                    die("Database connection was closed unexpectedly.");
                                }

                                // Fetch billing details and related appointment information
                                $query = "SELECT ab.BillingID, ab.AppointmentID, ab.TotalFee, ab.PaymentType, ab.PaymentStatus, 
                                                a.AppointmentType, a.AppointmentProcedure, a.AppointmentTreatment, 
                                                a.AppointmentLaboratory, a.AppointmentStatus, a.AppointmentDate,
                                                (SELECT COALESCE(SUM(p.PaymentAmount), 0) FROM payments p WHERE p.BillingID = ab.BillingID) AS TotalPaid
                                        FROM appointmentbilling ab
                                        JOIN appointment a ON ab.AppointmentID = a.AppointmentID
                                        WHERE ab.PatientID = ? 
                                        ORDER BY a.AppointmentDate DESC";

                                $stmt = mysqli_prepare($connection, $query);
                                if (!$stmt) {
                                    die("Query preparation failed: " . mysqli_error($connection));
                                }

                                mysqli_stmt_bind_param($stmt, "s", $patientID);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);

                                if (!$result) {
                                    die("Query execution failed: " . mysqli_error($connection));
                                }

                                if ($result->num_rows > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $billingID = htmlspecialchars($row['BillingID']);
                                        $appointmentID = htmlspecialchars($row['AppointmentID']);
                                        $appointmentType = htmlspecialchars($row['AppointmentType']);
                                        $procedure = htmlspecialchars($row['AppointmentProcedure']);
                                        $treatment = htmlspecialchars($row['AppointmentTreatment']);
                                        $laboratory = htmlspecialchars($row['AppointmentLaboratory']);
                                        $appointmentStatus = strtolower($row['AppointmentStatus']);
                                        $paymentType = htmlspecialchars($row['PaymentType']);
                                        $paymentStatus = strtolower($row['PaymentStatus']); // Convert to lowercase
                                        $totalFee = $row['TotalFee'];
                                        $totalPaid = $row['TotalPaid'] ?? 0; // Default to ₱0 if no payment is made
                                        $remainingBalance = max($totalFee - $totalPaid, 0); // Prevent negative balance
                                        $appointmentDate = date("Y-m-d", strtotime($row['AppointmentDate']));

                                        // **Logic: Show Penalty Fee if Appointment Status is 'penalty'**
                                        if ($appointmentStatus == 'penalty') {
                                            $displayFee = 2000; // Fixed penalty fee
                                        } else {
                                            $displayFee = $totalFee; // Normal total fee
                                        }

                                        // Determine the treatment name
                                        $treatmentName = $procedure ?: ($treatment ?: $laboratory ?: $appointmentType);

                                        // ✅ Show "Pay" button if payment status is "unpaid" OR "partial" (installment)
                                        if ($paymentStatus == 'unpaid' || $paymentStatus == 'partial') {
                                            $actionBtn = "<button class='pay_btn' onclick=\"openPaymentModal('$billingID')\">
                                                            <span class='material-symbols-outlined'>
                                                                <img src='icons/patient/credit-card.png' alt='Pay'>
                                                            </span>
                                                        </button>";
                                        } else {
                                            // ✅ Show "View Invoice" button if fully paid
                                            $actionBtn = "<button class='view_btn'>
                                                            <span class='material-symbols-outlined'>
                                                                <a href='generate_invoice.php?billing_id=" . urlencode($billingID) . "' target='_blank'>
                                                                    <img src='icons/patient/view_icon.png' alt='View'>
                                                                </a>
                                                            </span>
                                                        </button>";
                                        }

                                        // Convert Appointment Status to CSS class
                                        $statusClass = ($appointmentStatus == 'completed') ? "completed" : (($appointmentStatus == 'penalty') ? "penalty" : "ongoing");

                                        echo "<tr>
                                                <td>$billingID</td>
                                                <td>$appointmentID</td>
                                                <td>$treatmentName</td>
                                                <td>$appointmentType</td>
                                                <td class='status $statusClass'>". ucfirst($appointmentStatus) ."</td>
                                                <td>$paymentType</td>
                                                <td class='payment $paymentStatus'>". ucfirst($paymentStatus) ."</td>
                                                <td>₱" . number_format($displayFee, 2) . "</td>
                                                <td>₱" . number_format($totalPaid, 2) . "</td>
                                                <td>₱" . number_format($remainingBalance, 2) . "</td>
                                                <td class='cell_center_content'>$actionBtn</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='11' style='text-align: center;'>No billing records found.</td></tr>";
                                }

                                mysqli_stmt_close($stmt);
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Payment Modal -->
                    <div class="card-modal" id="paymentModal">
                        <div class="modal-content">
                            <span class="close-modal" onclick="closePaymentModal()">&times;</span>
                            <form id="paymentForm" onsubmit="processPayment(event)">
                                <input type="hidden" id="billingID" name="billingID"> <!-- Hidden Input -->
                                <div class="col">
                                    <h3 class="title" style="text-align: center;">Payment</h3>

                                    <!-- Select a Saved Card -->
                                    <div class="inputBox">
                                        <label for="cardType">Select a Saved Card:</label>
                                        <div class="card-options" id="userCardsContainer">
                                            <!-- Saved cards will be loaded here dynamically -->
                                        </div>
                                    </div>

                                    <!-- CVV Input -->
                                    <div class="inputBox">
                                        <label for="paymentCvv">Enter CVV:</label>
                                        <input type="password" id="paymentCvv" placeholder="Enter CVV" maxlength="4" required>
                                    </div>

                                    <div class="inputBox">
                                        <label for="amount">Amount:</label>
                                        <input type="number" id="amount" placeholder="Enter amount" min="1" required>
                                    </div>


                                    <!-- Submit Button -->
                                    <button type="submit" class="submit-btn">Pay Now</button>
                                </div>
                            </form>
                        </div>
                    </div>


            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('cardModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('cardModal').style.display = 'none';
            document.getElementById('cardForm').reset();
            clearErrors();
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('cardModal')) {
                closeModal();
            }
        }

        function formatCardNumber(input) {
            // Remove non-numeric characters
            let value = input.value.replace(/\D/g, '');
            // Add dashes after every 4 digits
            value = value.replace(/(\d{4})(?=\d)/g, '$1-');
            input.value = value;
        }

        function formatExpiry(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0,2) + '/' + value.substring(2);
            }
            input.value = value;
        }

        function validateNumeric(input) {
            input.value = input.value.replace(/\D/g, '');
        }

        function clearErrors() {
            const errorElements = document.getElementsByClassName('error-message');
            for (let element of errorElements) {
                element.style.display = 'none';
                element.textContent = '';
            }
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }

        function validateForm() {
            clearErrors();
            let isValid = true;

            const cardName = document.getElementById('cardName').value;
            const cardNum = document.getElementById('cardNum').value.replace(/-/g, '');
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;

            if (cardName.length < 3) {
                showError('cardNameError', 'Please enter a valid name');
                isValid = false;
            }

            if (cardNum.length !== 16) {
                showError('cardNumError', 'Please enter a valid 16-digit card number');
                isValid = false;
            }

            if (!/^\d{2}\/\d{2}$/.test(expiry)) {
                showError('expiryError', 'Please enter a valid expiry date (MM/YY)');
                isValid = false;
            }

            if (cvv.length < 3) {
                showError('cvvError', 'Please enter a valid CVV');
                isValid = false;
            }

            return isValid;
        }

        function handleSubmit(event) {
        event.preventDefault();
        
        // Collect form data
        const formData = new FormData();
        formData.append('cardType', document.querySelector('input[name="cardType"]:checked')?.value || '');
        formData.append('cardName', document.getElementById('cardName').value);
        formData.append('cardNumber', document.getElementById('cardNum').value.replace(/-/g, '')); // Remove dashes
        formData.append('expiryDate', document.getElementById('expiry').value);
        formData.append('cvv', document.getElementById('cvv').value);

        fetch('php/save_card.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Card added successfully!');
                closeModal();
            } else {
                alert('Error adding card: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding card. Please try again.');
        });
    }
    </script>

    <script>
       function openPaymentModal(billingID) {
            document.getElementById("billingID").value = billingID; // Set the Billing ID
            document.getElementById("paymentModal").style.display = "flex";
        }

        function closePaymentModal() {
            document.getElementById("paymentModal").style.display = "none";
            document.getElementById("paymentForm").reset();
        }


        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1-');
            input.value = value;
        }

        function processPayment(event) {
            event.preventDefault(); 

            let billingID = document.getElementById("billingID").value;
            let amount = parseFloat(document.getElementById("amount").value);
            let cvv = document.getElementById("paymentCvv").value;
            let selectedCard = document.querySelector('input[name="cardID"]:checked');

            if (!selectedCard) {
                alert("Please select a saved card.");
                return;
            }

            let cardID = selectedCard.value;

            if (isNaN(amount) || amount <= 0) {
                alert("Please enter a valid amount.");
                return;
            }

            let formData = new FormData();
            formData.append("billingID", billingID);
            formData.append("amount", amount);
            formData.append("cardID", cardID);
            formData.append("cvv", cvv);

            fetch("php/save_payment.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text()) // 🔹 First, read the response as text
            .then(text => {
                console.log("Raw Response:", text); // 🔹 Log raw response for debugging
                try {
                    let data = JSON.parse(text); // 🔹 Try parsing JSON
                    if (data.status === "success") {
                        alert("Payment successful!");
                        closePaymentModal();
                        location.reload(); 
                    } else {
                        alert("Error: " + data.message);
                    }
                } catch (error) {
                    console.error("JSON Parse Error:", error, text); // 🔹 Show error + response
                    alert("Unexpected server response. Check console for details.");
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                alert("Error processing payment. Please try again.");
            });
        }


        document.addEventListener("DOMContentLoaded", function() {
            let cardContainer = document.getElementById("userCardsContainer");

            fetch("php/get_user_cards.php")
            .then(response => response.json())
            .then(data => {
                if (data.status === "success" && data.cards.length > 0) {
                    cardContainer.innerHTML = ""; // Clear existing content

                    data.cards.forEach(card => {
                        let cardType = card.CardType.toLowerCase();
                        let imageExtensions = [".jpg", ".jpeg", ".png"];
                        let cardImagePath = "";

                        // Check which image extension exists
                        for (let ext of imageExtensions) {
                            let testImagePath = `icons/patient/${cardType}_icon${ext}`;
                            let img = new Image();
                            img.src = testImagePath;
                            img.onload = function() {
                                cardImagePath = testImagePath; // Set the valid image path
                                renderCard(card.CardID, cardType, card.CardNumber, cardImagePath);
                            };
                        }
                    });
                } else {
                    cardContainer.innerHTML = "<p>No saved cards found.</p>";
                }
            })
            .catch(error => console.error("Error fetching cards:", error));

            function renderCard(cardID, cardType, cardNumber, imagePath) {
                let cardElement = `
                    <input type="radio" id="card-${cardID}" name="cardID" value="${cardID}">
                    <label for="card-${cardID}">
                        <img src="${imagePath}" alt="${cardType}">
                        ${cardType} - ${cardNumber}
                    </label>
                `;
                cardContainer.innerHTML += cardElement;
            }
        });


    </script>

    <!--script for generating pdf-->
    <script>
        function generateInvoice(billingID) {
            fetch(`generate_invoice.php?billing_id=${billingID}`)
                .then(response => response.text())  // First, check response as text
                .then(text => {
                    console.log("Response:", text);  // Debugging
                    return JSON.parse(text);  // Parse as JSON
                })
                .then(data => {
                    if (data.status === "success") {
                        window.open(`php/invoices/${data.filename}`, '_blank'); // Open PDF
                    } else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(error => console.error("Error generating invoice:", error));
        }

    </script>

</body>
</html>