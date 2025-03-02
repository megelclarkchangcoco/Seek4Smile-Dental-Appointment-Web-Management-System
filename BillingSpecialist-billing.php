<?php
include 'php/connection.php';
session_start();

if (!isset($_SESSION['SpecialistID'])) {
    header("Location: login.php");
    exit;
}

$firstname = $_SESSION['Firstname'];
$lastname = $_SESSION['Lastname'];
$profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

// Order priority: unpaid bills first, then paid ones
$orderByPriority = "CASE WHEN pb.PaymentStatus = 'unpaid' THEN 1 ELSE 2 END, pb.CreatedAt DESC";

// Capture the search input
$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($connection, $_GET['search']);
    $searchQuery = " AND (
        pb.BillingID LIKE '%$search%' 
        OR p.Firstname LIKE '%$search%' 
        OR p.Lastname LIKE '%$search%' 
        OR a.AppointmentType LIKE '%$search%' 
        OR COALESCE(a.AppointmentLaboratory, a.AppointmentProcedure, a.AppointmentTreatment) LIKE '%$search%'
        OR pb.PaymentStatus LIKE '%$search%'
    )";
}

// SQL Query: Show unpaid first, then paid, in ascending order (oldest first)
$query = "
    SELECT 
        pb.BillingID, 
        pb.PatientID, 
        p.Firstname, 
        p.Lastname, 
        a.AppointmentID, 
        a.AppointmentType AS TreatmentName, 
        CASE 
            WHEN a.AppointmentType = 'Procedure' THEN a.AppointmentProcedure
            WHEN a.AppointmentType = 'Treatment' THEN a.AppointmentTreatment
            WHEN a.AppointmentType = 'Laboratory' THEN a.AppointmentLaboratory
            ELSE 'N/A'
        END AS TreatmentType,
        pb.PaymentType, 
        pb.PaymentStatus, 
        pb.TotalFee, 
        pb.CreatedAt, 
        COALESCE(SUM(pay.PaymentAmount), 0) AS PaidAmount,
        (pb.TotalFee - COALESCE(SUM(pay.PaymentAmount), 0)) AS RemainingBalance
    FROM appointmentbilling pb
    JOIN appointment a ON pb.AppointmentID = a.AppointmentID
    JOIN patient p ON pb.PatientID = p.PatientID
    LEFT JOIN payments pay ON pb.BillingID = pay.BillingID
    WHERE 1 $searchQuery
    GROUP BY pb.BillingID
    ORDER BY 
        CASE WHEN pb.PaymentStatus = 'unpaid' THEN 1 ELSE 2 END,  -- Prioritize unpaid
        pb.CreatedAt ASC  -- Sort by oldest date first
";

$result = mysqli_query($connection, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="icons/specialist/toothlogos.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/specialiststyle.css">
    <title>Billing</title>
</head>
<body> 
    <div id="wrapper">
        <div id="left_panel">
            <img id="logo" src="icons/specialist/logo_seek4smiles.png" alt="Logo">
        
            <label>
                <a href="BillingSpecialist-Homepage.php">
                    <img src="icons/specialist/home_icon.png" alt="Home"> Homepage
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-notification.php">
                    <img src="icons/specialist/notification_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-PatientInformation.php">
                    <img src="icons/specialist/medical_record_icon.png" alt="Patient Information"> Patient Information
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-billing.php">
                    <img src="icons/specialist/prescription.png" alt="Billings"> Billing
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-invoice.php">
                    <img src="icons/specialist/invoice.png" alt="Invoice"> Invoices
                </a>
            </label>
            <label>
                <a href="BillingSpecialist-profile.php">
                    <img src="icons/specialist/personnel_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="logout.php">
                    <img src="icons/specialist/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <div id="right_panel">
            <div id="header_patient">
                <div id="info_specialist" style="text-align: left;">
                    <p id="fullname_specialist"><?php echo htmlspecialchars($firstname  . ' ' . $lastname);?></p>
                    <p id="status_specialist">Medical Billing Specialist</p>
                </div>
                <img id="profile_icon_specialist" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="main-content">
                <h1>Billing Information</h1>
                <div class="search_group">
                <form method="GET" action="">
                        <div class="search_box">
                            <div class="row">
                                <input type="text" name="search" id="input-box" placeholder="Search billing records" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" autocomplete="off">
                            </div>
                        </div>
                    </form>
                    <button type="submit" id="search_btn">SEARCH</button>
                </div>

                <table class="appointment-requests">
                    <thead>
                    <tr>
                            <th>Billing ID</th>
                            <th>Patient Name</th>
                            <th>Treatment Name</th>
                            <th>Treatment Type</th>
                            <th>Payment Option</th>
                            <th>Payment Status</th>
                            <th>Remaining Balance</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                <tr class="<?= ($row['PaymentStatus'] == 'unpaid') ? 'highlight-unpaid' : '' ?>">
                                    <td><?= $row['BillingID'] ?></td>
                                    <td><?= $row['Firstname'] . ' ' . $row['Lastname'] ?></td>
                                    <td><?= ucfirst($row['TreatmentName']) ?></td>
                                    <td><?= $row['TreatmentType'] ?: 'N/A' ?></td>
                                    <td><?= ucfirst($row['PaymentType']) ?></td>
                                    <td id=".payment" class="<?= $row['PaymentStatus'] == 'unpaid' ? 'status unpaid' : 'status paid' ?>">
                                        <?= ucfirst($row['PaymentStatus']) ?>
                                    </td>
                                    <td>₱<?= number_format($row['RemainingBalance'], 2) ?></td>
                                    <td><?= date("M d, Y", strtotime($row['CreatedAt'])) ?></td>
                                    <td>₱<?= number_format($row['TotalFee'], 2) ?></td>
                                    <td class="cell_center_content">
                                        <a href="billing_invoice.php?billingID=<?= $row['BillingID'] ?>" target="_blank">
                                            <button class="view_btn">
                                                <span class="material-symbols-outlined">
                                                    <img src="icons/specialist/view.png" alt="View Invoice">
                                                </span>
                                            </button>
                                        </a>

                                        <?php if ($row['RemainingBalance'] > 0) : ?>
                                            <button class="pay_btn" onclick="openPaymentModal('<?= $row['BillingID'] ?>', '<?= $row['Firstname'] . ' ' . $row['Lastname'] ?>', '<?= $row['RemainingBalance'] ?>')">
                                                <span class="material-symbols-outlined">
                                                    <img src="icons/patient/credit-card.png" alt="Pay">
                                                </span>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" style="text-align: center;">No results found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="card-modal" id="paymentModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closePaymentModal()">&times;</span>
            <h2>Process Payment</h2>
            <form id="paymentForm" onsubmit="processPayment(event)">
                <input type="hidden" id="billingID" name="billingID">
                
                <div class="inputBox">
                    <label for="patientName">Patient Name:</label>
                    <input type="text" id="patientName" disabled>
                </div>

                <div class="inputBox">
                    <label for="remainingBalance">Remaining Balance:</label>
                    <input type="text" id="remainingBalance" disabled>
                </div>
                
                <div class="inputBox">
                    <label for="amount">Enter Amount:</label>
                    <input type="number" id="amount" placeholder="Enter amount" min="1" required>
                </div>

                <button type="submit" class="submit-btn">Confirm</button>
            </form>
        </div>
    </div>


    <script>
    // Open Payment Modal
    function openPaymentModal(billingID, patientName, remainingBalance) {
        let billingInput = document.getElementById("billingID");
        let patientInput = document.getElementById("patientName");
        let balanceInput = document.getElementById("remainingBalance");

        if (billingInput && patientInput && balanceInput) {
            billingInput.value = billingID;
            patientInput.value = patientName;
            balanceInput.value = remainingBalance;
            document.getElementById("paymentModal").style.display = "flex";
        } else {
            console.error("One or more modal input elements are missing!");
        }
    }

    // Close Payment Modal
    function closePaymentModal() {
        let modal = document.getElementById("paymentModal");
        let form = document.getElementById("paymentForm");

        if (modal) modal.style.display = "none";
        if (form) form.reset();
    }

    // Handle Payment Submission
    document.getElementById("paymentForm").addEventListener("submit", function(event) {
        event.preventDefault();

        let billingID = document.getElementById("billingID").value;
        let amount = parseFloat(document.getElementById("amount").value);
        let remainingBalance = parseFloat(document.getElementById("remainingBalance").value);

        if (!billingID || isNaN(amount) || amount <= 0) {
            alert("Enter a valid amount.");
            return;
        }

        let paymentStatus;
        if (amount >= remainingBalance) {
            paymentStatus = "paid";  // Fully paid
        } else {
            paymentStatus = "partial"; // Partial payment
        }

        fetch("php/process_payment.php", {
            method: "POST",
            body: JSON.stringify({
                billingID: billingID,
                amount: amount,
                paymentStatus: paymentStatus
            }),
            headers: { "Content-Type": "application/json" }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Payment processed successfully!");
                location.reload();
            } else {
                alert("Payment failed. Try again.");
            }
        })
        .catch(error => console.error("Error processing payment:", error));

        closePaymentModal();
    });

    // Close modal if user clicks outside of it
    window.onclick = function(event) {
        let modal = document.getElementById("paymentModal");
        if (event.target === modal) {
            closePaymentModal();
        }
    };
    </script>

</body>
</html>
