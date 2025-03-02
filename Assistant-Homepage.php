<?php 
    date_default_timezone_set('Asia/Manila'); // or your desired timezone

    include 'php/connection.php'; 
    session_start();

    if (!isset($_SESSION['AssistantID'])) {
        header("Location: login.php");
        exit;
    }

    $firstname = $_SESSION['Firstname'];
    $lastname = $_SESSION['Lastname'];
    $profile_img = !empty($_SESSION['img']) ? $_SESSION['img'] : 'img/user_default.png';

    // Query to calculate total inventory value
    $sql_total = "SELECT SUM(QuantityAvailable * UnitPrice) AS total_value FROM inventory";
    $result_total = $connection->query($sql_total);

    if ($result_total && $row_total = $result_total->fetch_assoc()) {
        $total_value = $row_total['total_value'];
    } else {
        $total_value = 0;
    }
    $formatted_total = number_format($total_value, 2);

    // Query to count items with QuantityAvailable below 100
    $sql_low_stock = "SELECT COUNT(*) AS low_stock_count FROM inventory WHERE QuantityAvailable < 100";
    $result_low_stock = $connection->query($sql_low_stock);

    // Check if the query failed
    if (!$result_low_stock) {
        die("Query failed: " . $connection->error);
    }

    $row = $result_low_stock->fetch_assoc();
    $low_stock_count = isset($row['low_stock_count']) ? $row['low_stock_count'] : 0;

    // count approved appointments
    $sql3 = "SELECT COUNT(*) AS approved_count FROM appointment WHERE appointmentStatus = 'Approved'";
    $result = $connection->query($sql3);

    if (!$result) {
        die("Query failed: " . $connection->error);
    }

    $row = $result->fetch_assoc();
    $approved_count = $row['approved_count'] ?? 0;

    // Query to fetch top 3 items with QuantityAvailable <= 100, sorted ascending (lowest first)
    $sql_low_stock = "SELECT ItemName, QuantityAvailable FROM inventory WHERE QuantityAvailable <= 100 ORDER BY QuantityAvailable ASC LIMIT 3";
    $result_low_stock = $connection->query($sql_low_stock);
    
    if (!$result_low_stock) {
        die("Query failed: " . $connection->error);
    }
    
    $low_stock_items = [];
    while ($row = $result_low_stock->fetch_assoc()) {
        $low_stock_items[] = $row;
    }

    // Query to fetch top 3 soon-to-expire items (expiring within 3 months)
    $sql_expiring = "SELECT ItemName, ExpiryDate 
    FROM inventory 
    WHERE ExpiryDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
    ORDER BY ExpiryDate ASC
    LIMIT 3";
    $result_expiring = $connection->query($sql_expiring);

    if (!$result_expiring) {
    die("Query failed: " . $connection->error);
    }

    $expiring_items = [];
    while ($row = $result_expiring->fetch_assoc()) {
    $expiring_items[] = $row;
    }

    // Query to fetch the top 5 items with the lowest QuantityAvailable
    $sql_top_usage = "SELECT ItemName, QuantityAvailable FROM inventory ORDER BY QuantityAvailable ASC LIMIT 5";
    $result_top_usage = $connection->query($sql_top_usage);
    
    if (!$result_top_usage) {
         die("Query failed: " . $connection->error);
    }
    
    $top_usage_items = [];
    while ($row = $result_top_usage->fetch_assoc()) {
        $top_usage_items[] = $row;
    }
    
    // This query counts appointments for the current week (using ISO week, starting Monday)
    $sql_weekly = "SELECT DAYNAME(appointmentDate) AS day, COUNT(*) AS count
    FROM appointment
    WHERE YEARWEEK(appointmentDate, 1) = YEARWEEK(CURDATE(), 1)
    GROUP BY DAYNAME(appointmentDate)";
    $result_weekly = $connection->query($sql_weekly);

    if (!$result_weekly) {
    die("Query failed: " . $connection->error);
    }

    // Initialize an array with all days set to 0
    $weekly_distribution = [
    "Monday"    => 0,
    "Tuesday"   => 0,
    "Wednesday" => 0,
    "Thursday"  => 0,
    "Friday"    => 0,
    "Saturday"  => 0,
    "Sunday"    => 0
    ];

    // Fill the array with counts from the query
    while ($row = $result_weekly->fetch_assoc()) {
    $day = $row['day'];
    $count = (int)$row['count'];
    $weekly_distribution[$day] = $count;
    }

    // Prepare labels (days) and values (counts)
    $days = array_keys($weekly_distribution);
    $counts = array_values($weekly_distribution);

    // Query to fetch the top 3 upcoming appointments (today or later)
    $sql_upcoming = "SELECT appointmentID, appointmentDate, TimeStart 
    FROM appointment 
    WHERE appointmentDate >= CURDATE() 
    ORDER BY appointmentDate ASC, TimeStart ASC 
    LIMIT 3";
    $result_upcoming = $connection->query($sql_upcoming);

    $upcomingAppointments = [];
    if ($result_upcoming) {
    while ($row = $result_upcoming->fetch_assoc()) {
    $upcomingAppointments[] = $row;
    }
    } else {
    die("Query failed: " . $connection->error);
    }

    // Query to fetch the top 5 patients with the most appointments.
    $sql_top_patients = "
        SELECT a.PatientID, CONCAT(p.Firstname, ' ', p.Lastname) AS FullName, COUNT(*) AS total_appointments
        FROM appointment a
        JOIN patient p ON a.PatientID = p.PatientID
        GROUP BY a.PatientID, p.Firstname, p.Lastname
        ORDER BY total_appointments DESC
        LIMIT 5
    ";

    $result_top_patients = $connection->query($sql_top_patients);

    if (!$result_top_patients) {
        die("Query failed: " . $connection->error);
    }

    $top_patients = [];
    while ($row = $result_top_patients->fetch_assoc()) {
        $top_patients[] = $row;
    }

    // Query to fetch the top 3 most common patient ages
    $sql_top_ages = "SELECT Age, COUNT(*) AS frequency FROM patient GROUP BY Age ORDER BY frequency DESC LIMIT 3";
    $result_top_ages = $connection->query($sql_top_ages);

    if (!$result_top_ages) {
        die("Query failed: " . $connection->error);
    }

    $top_ages = [];
    while ($row = $result_top_ages->fetch_assoc()) {
        $top_ages[] = $row;
    }

    // Query to fetch the top 3 appointment types by count
    $sql_top_types = "
    SELECT appointmentType, COUNT(*) AS frequency 
    FROM appointment
    GROUP BY appointmentType 
    ORDER BY frequency DESC 
    LIMIT 3
    ";
    $result = $connection->query($sql_top_types);

    if (!$result) {
    die("Query failed: " . $connection->error);
    }

    $topAppointmentTypes = [];
    while ($row = $result->fetch_assoc()) {
    $topAppointmentTypes[] = $row;
    }

    // Query to count each payment method
    $sql_payment_distribution = "SELECT paymentMethod, COUNT(*) AS count FROM payments GROUP BY paymentMethod";
    $result_payment_distribution = $connection->query($sql_payment_distribution);

    if (!$result_payment_distribution) {
        die("Query failed: " . $connection->error);
    }

    $payment_methods = [];
    $payment_counts = [];
    while ($row = $result_payment_distribution->fetch_assoc()) {
        $payment_methods[] = $row['paymentMethod'];
        $payment_counts[] = $row['count'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">
    <title>Dental Assistant - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
</head>
<body> 

    <!-- main div -->   
    <div id="wrapper">

        <!-- this the left panel located-->
        <div id="left_panel">
            <img id="logo" src="icons/dentalassistant/logo_seek4smiles.png" alt="Logo"> <!-- Add your logo image path -->
        
            <label>
                <a href="Assistant-homepage.php">
                    <img src="icons/dentalassistant/dashboard_icon.png" alt="Dashboard"> Dashboard
                </a>
            </label>
            <label>
                <a href="Assistant-notification.php">
                    <img src="icons/dentalassistant/notif_icon.png" alt="Notifications"> Notifications
                </a>
            </label>
            <label>
                <a href="Assistant-appointment.php">
                    <img src="icons/dentalassistant/calendar_icon.png" alt="Appointments"> Appointments
                </a>
            </label>
            <label>
                <a href="Assistant-patients.php">
                    <img src="icons/dentalassistant/patient_icon.png" alt="Patients"> Patients
                </a>
            </label>
            <label>
                <a href="Assistant-inventory.php">
                    <img src="icons/dentalassistant/inventory_icon.png" alt="Inventory"> Inventory
                </a>
            </label>
            <label>
                <a href="Assistant-profile.php">
                    <img src="icons/dentalassistant/profile_icon.png" alt="Profile"> Profile
                </a>
            </label>
            <label>
                <a href="Logout.php">
                    <img src="icons/dentalassistant/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">

            <!--this for header where the profile icon located----->
            <div id="header">
                <div id="info" style="text-align: left;">
                    <p id="fullname"><?= $firstname ?> <?= $lastname ?></p>
                    <p id="status">Dental Assistant</p>
                </div>
                <img id="profile_icon" src="<?php echo htmlspecialchars($profile_img, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon">
            </div>

            <!-- this div where the main content located-->
            <div id="content">
                <h1>Dashboard</h1>
                <div class="dateandtime_container">
                    <div class="date_container">
                        <span class="material-symbols-outlined"> calendar_month </span>
                        <p id="date"><?php echo date("l, d F Y"); ?></p>
                    </div>
                    <div class="time_container">
                        <span class="material-symbols-outlined"> schedule </span>
                        <p id="time"><?php echo date("h:i:s A"); ?></p>
                    </div>
                </div>

                <div class="alldata">
                    <div class="data"> 
                        <div class="quantities"> 
                            <div class="quantity"> <!-- Numbers-->
                                <h2 style="font-size: 40px;">₱<?php echo $formatted_total; ?></>
                                <p>Total Inventory Value</p>
                            </div>
                            <div class="quantity">
                                <h2 style="font-size: 40px;"><?php echo $low_stock_count; ?></h2>
                                <p>Items Low on Stock</p>
                            </div>
                            <div class="quantity">
                                <h2><?php echo $approved_count; ?></h2>
                                <p>Approved Appointments Today</p>
                            </div>
                            <div class="quantity">
                                <h2>22,123</h2>
                                <p>On-time Supplier Rate</p>
                            </div>
                        </div>
                        <div class="distribution"> <!-- Chart -->
                            <h3>Weekly Appointment Distribution</h3>
                            <div class="barchart_container">
                                <canvas id="weekly-appointment-distribution-chart" style="height: 295px;"></canvas>
                            </div>
                        </div>

                        <div class="piecharts">
                            <div class="demographics">
                                <h3>Top Appointment Types</h3>
                                <div class="toprank">
                                    <div class="topnumrank">
                                        <?php 
                                        // Print rank numbers dynamically (1, 2, 3)
                                        for ($i = 1; $i <= count($topAppointmentTypes); $i++) {
                                            echo "<p>$i</p>";
                                        }
                                        ?>
                                    </div>

                                    <div class="topitemsranked">
                                        <?php 
                                        // Loop through each top appointment type and display it with frequency
                                        foreach ($topAppointmentTypes as $type) {
                                            echo "<p>" . htmlspecialchars($type['appointmentType']) . " (" . htmlspecialchars($type['frequency']) . ")</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="demographics">
                                <h3>Payment Type Distribution</h3>
                                <div class="piechart_container">
                                    <canvas id="piec_paymenttype" height="200"></canvas>
                                </div>
                            </div>

                            <div class="demographics">
                                <h3>Top 3 Most Common Patient Ages</h3>
                                <div class="toprank">
                                    <div class="topnumrank">
                                        <?php 
                                        // Display rank numbers dynamically based on number of results
                                        $rank = 1;
                                        foreach ($top_ages as $ageInfo) {
                                            echo "<p>$rank</p>";
                                            $rank++;
                                        }
                                        ?>
                                    </div>

                                    <div class="topitemsranked">
                                        <?php 
                                        // Display each age and its frequency
                                        foreach ($top_ages as $ageInfo) {
                                            echo "<p>Age: " . htmlspecialchars($ageInfo['Age']) . " (Count: " . htmlspecialchars($ageInfo['frequency']) . ")</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="demographics">
                                <h3>Appointment Status Distribution</h3>
                                <div class="toprank">
                                    <div class="topnumrank">
                                        <?php 
                                        // Display rank numbers (1 to 5)
                                        for ($i = 1; $i <= count($top_patients); $i++) {
                                            echo "<p>$i</p>";
                                        }
                                        ?>
                                    </div>

                                    <div class="topitemsranked">
                                        <?php 
                                        // Loop through the top patients and display their FullName and PATID
                                        foreach ($top_patients as $patient) {
                                            echo "<p>" . htmlspecialchars($patient['FullName']) . " (" . htmlspecialchars($patient['PatientID']) . ")</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
                    <div class="lists">
                        <div class="top">
                            <h3>Low Stock Alerts</h3>
                            <div class="rank">
                                    <div class="numrank">
                                        <?php 
                                        // Display rank numbers from 1 to count of low_stock_items
                                        for ($i = 1; $i <= count($low_stock_items); $i++) {
                                            echo "<p>$i</p>";
                                        }
                                        ?>
                                    </div>

                                    <div class="itemsranked">
                                        <?php 
                                        foreach ($low_stock_items as $item) {
                                            // Display item name and quantity if desired
                                            echo "<p>" . htmlspecialchars($item['ItemName']) . " (Stock: " . htmlspecialchars($item['QuantityAvailable']) . ")</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                        </div>
                        <div class="top">
                            <h3>Soon-to-Expire Items</h3>
                            <div class="rank">
                                    <div class="numrank">
                                        <?php 
                                        // Print rank numbers for each item
                                        for ($i = 1; $i <= count($expiring_items); $i++) {
                                            echo "<p>$i</p>";
                                        }
                                        ?>
                                    </div>

                                    <div class="itemsranked">
                                        <?php 
                                        // Print each item's name and expiry date
                                        foreach ($expiring_items as $item) {
                                            $expiryFormatted = date("F d, Y", strtotime($item['ExpiryDate']));
                                            echo "<p>" . htmlspecialchars($item['ItemName']) . " (Expires: " . $expiryFormatted . ")</p>";
                                        }
                                        ?>
                                    </div>
                            </div>
                        </div>
                        <div class="top">
                            <h3>Upcoming Procedure Resource Forecast</h3>
                            <div class="rank">
                                <div class="numrank">
                                    <?php 
                                    // Display rank numbers (1, 2, 3) based on the count of upcoming appointments
                                    for ($i = 1; $i <= count($upcomingAppointments); $i++): 
                                    ?>
                                        <p><?php echo $i; ?></p>
                                    <?php endfor; ?>
                                </div>

                                <div class="itemsranked">
                                    <?php 
                                    // Loop through each upcoming appointment and display details
                                    foreach ($upcomingAppointments as $appointment): 
                                        // Format the appointment date and time
                                        $formattedDate = date("l, d F Y", strtotime($appointment['appointmentDate']));
                                        $formattedTime = date("h:i A", strtotime($appointment['TimeStart']));
                                    ?>
                                        <p><?php echo htmlspecialchars($formattedDate . " at " . $formattedTime); ?></p>
                                    <?php endforeach; ?>

                                    <?php 
                                    // If no upcoming appointments, display a placeholder message
                                    if (empty($upcomingAppointments)) {
                                        echo "<p>No upcoming appointments</p>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="top">
                            <h3>Top 5 Used Items</h3>
                            <div class="rank">
                                    <div class="numrank">
                                        <?php 
                                        // Display rank numbers from 1 to the number of items fetched
                                        for ($i = 1; $i <= count($top_usage_items); $i++) {
                                            echo "<p>$i</p>";
                                        }
                                        ?>
                                    </div>
                                    <div class="itemsranked">
                                        <?php 
                                        // Display each item's name along with its available quantity
                                        foreach ($top_usage_items as $item) {
                                            echo "<p>" . htmlspecialchars($item['ItemName']) . " (Qty: " . htmlspecialchars($item['QuantityAvailable']) . ")</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>

                </div>

 <!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pass PHP arrays to JavaScript
    const weeklyLabels = <?php echo json_encode($days); ?>;
    const weeklyCounts = <?php echo json_encode($counts); ?>;

    const ctx = document.getElementById('weekly-appointment-distribution-chart').getContext('2d');
    const weeklyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: weeklyLabels,
            datasets: [{
                label: 'Appointments',
                data: weeklyCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0 // to display whole numbers
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

<!-- Include Chart.js from a CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxx = document.getElementById('piec_paymenttype').getContext('2d');
    const paymentChart = new Chart(ctxx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($payment_methods); ?>,
            datasets: [{
                data: <?php echo json_encode($payment_counts); ?>,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

    
</body>
</html>