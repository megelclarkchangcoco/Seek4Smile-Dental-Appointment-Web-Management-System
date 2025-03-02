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

    // Query  Total Generated Income
    $totalIncomeQuery = "SELECT SUM(PaymentAmount) AS TotalGeneratedIncome FROM payments";
    $totalIncomeResult = mysqli_query($connection, $totalIncomeQuery);
    $totalIncome = mysqli_fetch_assoc($totalIncomeResult)['TotalGeneratedIncome'];

    // Query  Collected Payments this Month
    $collectedThisMonthQuery = "
        SELECT SUM(PaymentAmount) AS CollectedThisMonth 
        FROM payments 
        WHERE YEAR(PaymentDate) = YEAR(CURRENT_DATE()) 
        AND MONTH(PaymentDate) = MONTH(CURRENT_DATE())";
    $collectedThisMonthResult = mysqli_query($connection, $collectedThisMonthQuery);
    $collectedThisMonth = mysqli_fetch_assoc($collectedThisMonthResult)['CollectedThisMonth'];

    // Query  Total Outstanding Balance
    $outstandingBalanceQuery = "
        SELECT SUM(TotalFee) AS TotalOutstandingBalance 
        FROM appointmentbilling 
        WHERE PaymentStatus = 'unpaid'";
    $outstandingBalanceResult = mysqli_query($connection, $outstandingBalanceQuery);
    $outstandingBalance = mysqli_fetch_assoc($outstandingBalanceResult)['TotalOutstandingBalance'];

    // Query to fetch monthly payment data
    $monthlyIncomeQuery = "
    SELECT 
        DATE_FORMAT(PaymentDate, '%Y-%m') AS Month, 
        SUM(PaymentAmount) AS TotalPayment 
    FROM payments 
    GROUP BY DATE_FORMAT(PaymentDate, '%Y-%m') 
    ORDER BY Month";
    $monthlyIncomeResult = mysqli_query($connection, $monthlyIncomeQuery);

    // Prepare data for the chart
    $labels = [];
    $data = [];

    while ($row = mysqli_fetch_assoc($monthlyIncomeResult)) {
    $date = DateTime::createFromFormat('Y-m', $row['Month']);
    $labels[] = $date->format('M'); // Format as "Jan", "Feb", etc.
    $data[] = $row['TotalPayment']; // Total payment for the month
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
    <title>Billing Homepage</title>
</head>
<body> 
    <div id="wrapper">
        <!-- Left panel code remains the same -->
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
            <div id="header">
                <div id="info">
                    <p id="fullname"><?php echo htmlspecialchars($firstname . ' ' . $lastname); ?></p>
                    <p id="status">Medical Billing Specialist</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <div class="dashboard-container">
                <div class="slogan-container">
                    <div class="slogan-content">
                        <div class="slogan-h3">
                            <!--<h3>Book an appointment</h3>-->
                        </div>
                        <div class="slogan-p">
                            <!-- <p>Book anytime, anywhere.</p> -->
                        </div>
                    </div>
                </div>

                <div class="dashboard-header">
                    <h1>Welcome, <?php echo htmlspecialchars($firstname);?>!</h1>
                </div>
                
                <!-- <div class="stats-container">
                    <div class="stat-card">
                        <h2>500</h2>
                        <p>Total Paid Payments</p>
                    </div>
                    <div class="stat-card">
                        <h2>354</h2>
                        <p>Total Unpaid Payments</p>
                    </div>
                </div> -->

                <div class="graph-section">
                    <div class="graph-container">
                        <h3>Monthly Generated Income</h3>
                        <canvas id="incomeChart"></canvas>
                    </div>
                    <div class="financial-summary">
                        <div class="summary-card">
                            <h3>₱<?= number_format($totalIncome, 2) ?></h3>
                            <p>Total Generated Income</p>
                        </div>
                        <div class="summary-card">
                            <h3>₱<?= number_format($outstandingBalance, 2) ?></h3>
                            <p>Total Outstanding Balance</p>
                        </div>
                        <div class="summary-card">
                            <h3>₱<?= number_format($collectedThisMonth, 2) ?></h3>
                            <p>Collected Payments this Month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('incomeChart').getContext('2d');
                
                // Get data from PHP
                const monthlyLabels = <?= json_encode($labels) ?>; // Months (e.g., ["Jan", "Feb", ...])
                const monthlyData = <?= json_encode($data) ?>; // Total payments (e.g., [5000, 7000, ...])

                const chartData = {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Monthly Income',
                        data: monthlyData,
                        borderColor: '#0085AA',
                        backgroundColor: 'rgba(0, 133, 170, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                };

                const config = {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f0f0f0'
                                },
                                title: {
                                    display: true,
                                    text: 'Amount (₱)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                };

                new Chart(ctx, config);
            });

    </script>
</body>
</html>