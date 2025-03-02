<?php
    include 'php/connection.php';
    session_start();

    if (!isset($_SESSION['AssistantID'])) {
        header('Location: index.html');
        exit();
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

    // Count active suppliers
    $sql_active = "SELECT COUNT(*) AS active_supplier_count FROM suppliers WHERE Status = 'Active'";
    $result_active = $connection->query($sql_active);

    if (!$result_active) {
        die("Query failed: " . $connection->error);
    }

    $row_active = $result_active->fetch_assoc();
    $active_supplier_count = $row_active['active_supplier_count'] ?? 0;

    // Initialize an array for 12 months (January to December) with zero values.
    $usage_data = array_fill(1, 12, 0);

    // This query assumes that the LastRestockedDate column is of type DATE or DATETIME.
    $sql = "SELECT MONTH(LastRestockedDate) AS month, SUM(QuantityAvailable) AS total_usage 
            FROM inventory 
            GROUP BY MONTH(LastRestockedDate)";
    $result = $connection->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $month = (int)$row['month'];
            $usage_data[$month] = $row['total_usage'];
        }
    } else {
        die("Query failed: " . $connection->error);
    }

    // Prepare month labels and corresponding values.
    $months = [];
    $values = [];
    for ($i = 1; $i <= 12; $i++) {
        // Full month names (e.g., "January", "February", etc.)
        $months[] = date("F", mktime(0, 0, 0, $i, 10));
        $values[] = $usage_data[$i];
    }

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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/dentalassistantstyle.css">

    <link rel="stylesheet" href="css/da_inventory_dashboard.css">
    <link rel="stylesheet" href="css/da_inventory_itemlist.css">
    <link rel="stylesheet" href="css/da_inventory_supplier list.css">
    <link rel="stylesheet" href="css/da_inventory_usage.css">
    <link rel="icon" type="image/x-icon" href="icons/patient/toothlogos.png">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <title>Dental Assistant - Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <style>
        .sub-navigation {
        display: flex;
        gap: 20px;
        margin-left: 36px;

    } 

    .sub-navigation a {
        color:#0085AA;
        cursor: pointer;
        padding: 10px;
        margin: 0;
        font-size: 16px;
        text-decoration: none;
        
    }


    .sub-navigation a.active {
        border-bottom: 2px solid #0085AA;
        color: #0085AA;
        font-weight: bold;
    }

    /* Hide sections by default */
    .section {
        display: none;
    }

    /* Class for the visible section */
    .section.active {
        display: block;
    }

    /* Align items in header with selection */
    #header_with_selection {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 40px;
        background-color: white;
        border-bottom: 1px solid #e0e0e0;
        padding-top: 34px;
        padding-bottom: 0px;
        width: 100%;
    
    }

    .profile_left {
        display: flex;
        align-items: center;
        gap: 3px;
        justify-content: center;
        padding-bottom: 22px;
    }

    /*modal css */
/* MODAL BACKGROUND */
.card-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
}

/* MODAL CONTENT */
.modal-content {
    background: #fff;
    border-radius: 8px;
    width: 500px; /* Adjusted width */
    padding: 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* FLEX CONTAINER */
.form-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 10px;
}

/* TWO-COLUMN FORM LAYOUT */
.form-group {
    display: flex;
    flex-direction: column;
    width: 48%; /* Each group takes 48% width to fit two in a row */
}

/* ENSURE LABEL AND INPUT SIZES MATCH */
.form-group label {
    font-size: 14px;
    font-weight: bold;
    color: #0085AA;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    height: 50px; /* Adjust height for better spacing */
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box; /* Ensures inputs don't shrink */
}

/* INPUT FIELDS */
input {
    width: 100%; /* Ensures full width inside container */
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}



/* FIX DATE INPUT */
input[type="date"] {
    width: 100%;
}

/* FORM FOOTER */
.form-footer {
    display: flex;
    justify-content: flex-end; /* Align button to the right */
    margin-top: 15px;
}

/* SUBMIT BUTTON */
.submit-btn {
    background: #0085AA;
    color: white;
    font-weight: bold;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    margin-top: 10px;
    margin-left: 450px;
}
.update_btn{
    align-items: center;
    justify-content: center;
    display: flex;
    gap: 5px;
    cursor: pointer;
    border-radius: 15px;
    color:#0085AA;
    font-size: 16px;
    background-color: #EFF8FF;
    border: 1px solid #0085AA;
    padding: 10px 20px;
    font-weight: bold;
}


#add_usage_btn{
    align-items: center;
    justify-content: center;
    display: flex;
    gap: 5px;
    cursor: pointer;
    border-radius: 15px;
    color:#0085AA;
    font-size: 16px;
    background-color: #EFF8FF;
    border: 1px solid #0085AA;
    padding: 10px 20px;
    font-weight: bold;
}

.edit_usage_btn{
    align-items: center;
    justify-content: center;
    display: flex;
    gap: 5px;
    cursor: pointer;
    border-radius: 15px;
    color:#0085AA;
    font-size: 16px;
    background-color: #EFF8FF;
    border: 1px solid #0085AA;
    padding: 10px 20px;
    font-weight: bold;
}

/*Search properties & hover settings */
#usage-search-btn {
    cursor: pointer;
    width: 160px;
    height: 49px;
    border-radius: 5px;
    font-size: 18px;
    font-weight: bold;
    background-color: #0085AA;
    color: white;
    border: 0;
}
#usage-search-btn:hover {
    background-color: #35C3A3; /*Change bg */
}

#item-list-search-btn{
    cursor: pointer;
    width: 160px;
    height: 49px;
    border-radius: 5px;
    font-size: 18px;
    font-weight: bold;
    background-color: #0085AA;
    color: white;
    border: 0;
}
#item-list-search-btn:hover {
    background-color: #35C3A3; /*Change bg */   
}

#Status{
        width: 100%; /* Ensures full width inside container */
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;

}

#supplier-search-btn{
    cursor: pointer;
    width: 160px;
    height: 49px;
    border-radius: 5px;
    font-size: 18px;
    font-weight: bold;
    background-color: #0085AA;
    color: white;
    border: 0;
}

#supplier-search-btn:hover {
    background-color: #35C3A3; /*Change bg */   
}
    </style>
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
                <a href="logout.php">
                    <img src="icons/dentalassistant/signout_icon.png" alt="Sign Out"> Sign Out
                </a>
            </label>
        </div>

        <!-- this div where the rigth panel located and the other feature-->
        <div id="right_panel">
             <!--this for header where the profile icon located----->
             <div id="header_with_selection">
                <div class="sub-navigation">
                    <a href="#" onclick="showRightPanelSection('inventory_dashboard_section')">Inventory Dashboard</a>
                    <a href="#" onclick="showRightPanelSection('item_list_section')">Item List</a>
                    <a href="#" onclick="showRightPanelSection('monitortrackusage_section')">Monitor and Track Usage</a>
                    <a href="#" onclick="showRightPanelSection('suppliers_section')">Suppliers</a>
                </div>
                 <div class="profile_left">
                    <div id="info" style="text-align: left;">
                        <p id="fullname">Carlos Sainz</p>
                        <p id="status">Dental Assistant</p>
                    </div>
                    <img id="profile_icon" src="img/CarlosID.png" alt="Profile Icon">

                 </div>   
                
            </div>

            <!--inventory dashboard-->
            <div id="inventory_dashboard_section" class="section">
                <h1>Inventory Dashboard</h1>
                <div class="inventory_dashboard_container">
                    <div class="alldata"> 
                        <div class="data"> 
                            <div class="quantities"> 
                                <div class="quantity"> <!-- Numbers-->
                                     <h2 style="font-size: 40px;">₱<?php echo $formatted_total; ?></h2>

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
                                    <h2><?php echo $active_supplier_count; ?></h2>
                                    <p>Total Active Supplier</p>
                                </div>
                            </div>
                            <div class="distribution_inventory" style="height:300px;">
                                <h3>Monthly Inventory Usage</h3>
                                <div class="barchart_container">
                                    <canvas id="monthly-inventory-chart" style="height:100px;"></canvas> 
                                </div>
                            </div>

                            <!-- <div class="piecharts">
                                <div class="demographics_inventory">
                                    <h3>Inventory Costs Insights</h3>
                                    <p>Total Monthly Spend: </p>
                                    <div class="piechart_container">
                                        <canvas id="piec_paymenttype" height="200"></canvas>
                                    </div>
                                </div>

                                <div class="demographics_inventory">
                                    <h3>Supplier Performance Summary</h3>
                                    <p>Average Delivery Time</p>
                                    <div class="piechart_container">
                                        <canvas id="piec_paymenttype" height="200"></canvas>
                                    </div>
                                </div>
    
                            </div> -->
        
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

                            <!-- <div class="top">
                                <h3>Upcoming Procedure Resource Forecast</h3>
                                <div class="rank">
                                    <div class="numrank">
                                        <p>1</p>
                                        <p>2</p>
                                        <p>3</p>
        
                                    </div>
        
                                    <div class="itemsranked">
                                        <p>Item 1</p>
                                        <p>Item 2</p>
                                        <p>Item 3</p>
                                    </div>
                                </div>
                            </div> -->
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

            <!--item list-->
            <div id="item_list_section" class="section">
                <h1>Inventory Item List</h1>

                <div class="top_group">
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <!-- Unique ID for Item List search input -->
                                <input type="text" id="item-list-search-input" placeholder="Search item or item ID" style="border: none; outline: none;" autocomplete="off">
                            </div>
                        </div>
                        <!-- Unique ID for Item List search button -->
                        <button id="item-list-search-btn">SEARCH</button>
                    </div>
                    
                    <div class="add_update_buttons"> 
                        <button id="add_btn"> 
                            <span class="material-symbols-outlined">add</span> ADD NEW
                        </button>
                    </div>

                </div>
                

            
                <?php
                include 'php/connection.php';

                // Fetch all inventory items
                $sql = "SELECT * FROM inventory ORDER BY InventoryID ASC";
                $result = $connection->query($sql);
                ?>

                <table class="item_list_table">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Quantity Available</th>
                            <th>Reorder Points</th>
                            <th>Unit Type</th>
                            <th>Unit Price</th>
                            <th>Supplier</th>
                            <th>Expiry Date</th>
                            <th>Last Restocked Date</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['InventoryID']; ?></td>
                            <td><?php echo $row['ItemName']; ?></td>
                            <td><?php echo $row['Category']; ?></td>
                            <td><?php echo $row['Description']; ?></td>
                            <td><?php echo $row['QuantityAvailable']; ?></td>
                            <td><?php echo $row['ReorderPoints']; ?></td>
                            <td><?php echo $row['UnitType']; ?></td>
                            <td>₱<?php echo number_format($row['UnitPrice'], 2); ?></td>
                            <td><?php echo $row['Supplier']; ?></td>
                            <td><?php echo $row['ExpiryDate']; ?></td>
                            <td><?php echo $row['LastRestockedDate']; ?></td>
                            <td><?php echo $row['Location']; ?></td>
                            <td>
                                <button class="update_btn" data-id="<?php echo $row['InventoryID']; ?>" 
                                        data-name="<?php echo $row['ItemName']; ?>" 
                                        data-category="<?php echo $row['Category']; ?>" 
                                        data-description="<?php echo $row['Description']; ?>" 
                                        data-quantity="<?php echo $row['QuantityAvailable']; ?>" 
                                        data-reorder="<?php echo $row['ReorderPoints']; ?>" 
                                        data-unit="<?php echo $row['UnitType']; ?>" 
                                        data-price="<?php echo $row['UnitPrice']; ?>" 
                                        data-supplier="<?php echo $row['Supplier']; ?>" 
                                        data-expiry="<?php echo $row['ExpiryDate']; ?>" 
                                        data-restocked="<?php echo $row['LastRestockedDate']; ?>" 
                                        data-location="<?php echo $row['Location']; ?>">
                                    UPDATE STOCK LEVEL
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <?php
                $connection->close();
                ?>



                <!-- MODAL -->
                <div class="card-modal" id="modal">
                    <div class="modal-content">
                        <span class="close-modal" id="close_modal">&times;</span>
                        <h2>Add New Inventory Item</h2>

                        <form id="inventory_form">
                            <div class="inputBox">
                                <label for="ItemName">Item Name:</label>
                                <input type="text" id="ItemName" name="ItemName" required>
                            </div>

                            <div class="inputBox">
                                <label for="Category">Category:</label>
                                <input type="text" id="Category" name="Category" required>
                            </div>

                            <div class="inputBox">
                                <label for="Description">Description:</label>
                                <input type="text" id="Description" name="Description">
                            </div>

                            <div class="inputBox">
                                <label for="QuantityAvailable">Quantity Available:</label>
                                <input type="number" id="QuantityAvailable" name="QuantityAvailable" required>
                            </div>

                            <div class="inputBox">
                                <label for="ReorderPoints">Reorder Points:</label>
                                <input type="number" id="ReorderPoints" name="ReorderPoints" required>
                            </div>

                            <div class="inputBox">
                                <label for="UnitType">Unit Type:</label>
                                <input type="text" id="UnitType" name="UnitType" required>
                            </div>

                            <div class="inputBox">
                                <label for="UnitPrice">Unit Price:</label>
                                <input type="number" id="UnitPrice" name="UnitPrice" step="0.01" required>
                            </div>

                            <div class="inputBox">
                                <label for="Supplier">Supplier:</label>
                                <input type="text" id="Supplier" name="Supplier" required>
                            </div>

                            <div class="inputBox">
                                <label for="ExpiryDate">Expiry Date:</label>
                                <input type="date" id="ExpiryDate" name="ExpiryDate" required>
                            </div>

                            <div class="inputBox">
                                <label for="LastRestockedDate">Last Restocked Date:</label>
                                <input type="date" id="LastRestockedDate" name="LastRestockedDate" required>
                            </div>

                            <div class="inputBox">
                                <label for="Location">Location:</label>
                                <input type="text" id="Location" name="Location" required>
                            </div>

                            <button type="submit" class="submit-btn">Submit</button>
                        </form>

                        </form>
                    </div>
                </div>

            </div>

            <!--Monitor and Track Usage-->
            <div id="monitortrackusage_section" class="section">
                <h1>Monitor and Track Usage</h1>
                <div class="top_group">
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <!-- Unique ID for Usage search input -->
                                <input type="text" id="usage-search-input" placeholder="Search for patient" style="border: none; outline: none;" autocomplete="off">
                            </div>
                        </div>
                        <!-- Unique ID for Usage search button -->
                        <button id="usage-search-btn" onclick="handleUsageSearch()">SEARCH</button>
                    </div>
                    <!-- Corrected Button ID -->
                        <div class="add_edit">
                            <button id="add_usage_btn"><span class="material-symbols-outlined">add</span>ADD</button>
                        </div>




                </div>
                <table class= "usage_table">
                    <thead>
                        <tr>
                            <th>Usage ID</th>
                            <th>Patient Name</th>
                            <th>Procedure</th>
                            <th>Date of Procedure</th>
                            <th>Item Used</th>
                            <th>Quantity</th>
                            <th>Unit Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="usage-table-body">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><button class="edit_usage_btn"><span class="material-symbols-outlined">edit</span>EDIT</button></td>

                        </tr>
                    </tbody>
                </table>

                <!-- MODAL FOR USAGE TABLE -->
                <div class="card-modal" id="usage_modal">
                    <div class="modal-content">
                        <span class="close-modal" id="close_usage_modal">&times;</span>
                        <h2 id="usage_modal_title">Add Usage Record</h2>

                        <form id="usage_form">
                            <div class="form-container">
                                <div class="form-group">
                                    <label for="PatientID">Patient ID:</label>
                                    <input type="text" id="PatientID" name="PatientID" required>

                                    <label for="ProcedureName">Procedure:</label>
                                    <input type="text" id="ProcedureName" name="ProcedureName" required>

                                    <label for="DateOfProcedure">Date of Procedure:</label>
                                    <input type="date" id="DateOfProcedure" name="DateOfProcedure" required>
                                </div>

                                <div class="form-group">
                                    <label for="ItemUsed">Item Used:</label>
                                    <input type="text" id="ItemUsed" name="ItemUsed" required> 

                                    <label for="Quantity">Quantity:</label>
                                    <input type="number" id="Quantity" name="Quantity" required> 

                                    <label for="UnitType">Unit Type:</label>
                                    <input type="text" id="UnitType" name="UnitType" required> 
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>


                

            </div>

            <!--Suppliers-->
            <div id="suppliers_section" class="section">
                <h1>Suppliers</h1>
                <div class="top_group">
                    <div class="search_group">
                        <div class="search_box">
                            <div class="row">
                                <span class="material-symbols-outlined">search</span>
                                <input type="text" id="supplier-search-input" placeholder="Search supplier name or supplier ID" style="border: none; outline: none;" autocomplete="off">
                            </div>
                        </div>
                        <button id="supplier-search-btn">SEARCH</button>
                    </div>
                    <div class="add_edit">
                        <button id="add_supplier_btn"><span class="material-symbols-outlined">add</span>ADD</button>
                    </div>
                </div>

                <table class="supplier_list">
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Supplier Name</th>
                            <th>Status</th>
                            <th>Contact Person</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Postal Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="supplier-table-body">
                        <!-- Rows will be dynamically populated here -->
                    </tbody>
                </table>

                <!-- SUPPLIER MODAL -->
                <div class="card-modal" id="supplier_modal">
                    <div class="modal-content">
                        <span class="close-modal" id="close_supplier_modal">&times;</span>
                        <h2 id="supplier_modal_title">Add Supplier</h2>
                        <form id="supplier_form">
                            <div class="form-container">
                                <div class="form-group">
                                    <label for="SupplierName">Supplier Name:</label>
                                    <input type="text" id="SupplierName" name="SupplierName" required>

                                    <label for="Status">Status:</label>
                                    <select id="Status" name="Status" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>

                                    <label for="ContactPerson">Contact Person:</label>
                                    <input type="text" id="ContactPerson" name="ContactPerson" required>

                                    <label for="ContactNumber">Contact Number:</label>
                                    <input type="text" id="ContactNumber" name="ContactNumber" maxlength="9" pattern="\d{9}" title="Contact Number must be exactly 9 digits." required>
                                </div>
                                <div class="form-group">
                                    <label for="Email">Email:</label>
                                    <input type="email" id="Email" name="Email" required>

                                    <label for="Address">Address:</label>
                                    <input type="text" id="Address" name="Address" required>

                                    <label for="City">City:</label>
                                    <input type="text" id="City" name="City" required>

                                    <label for="PostalCode">Postal Code:</label>
                                    <input type="text" id="PostalCode" name="PostalCode" maxlength="4" pattern="\d{4}" title="Postal Code must be exactly 4 digits." required>

                                </div>
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="submit-btn">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            

        </div>
    </div>
    <!-- script for subnavigation bar-->
    <script>
        function showRightPanelSection(sectionId) {
            // Get all sections in the right panel
            const sections = document.querySelectorAll('#right_panel .section');
    
            // Hide all sections and remove 'active' class
            sections.forEach(section => section.classList.remove('active'));
    
            // Show the selected section
            const selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.add('active');
            }
    
            // Update the active state for navigation links
            const navLinks = document.querySelectorAll('.sub-navigation a');
            navLinks.forEach(link => link.classList.remove('active'));
    
            // Highlight the clicked link
            const clickedLink = document.querySelector(`[onclick="showRightPanelSection('${sectionId}')"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
        }
    
        // Initialize on page load
        window.onload = function () {
            const sections = document.querySelectorAll('#right_panel .section');
            sections.forEach(section => section.classList.remove('active'));
    
            // Show the default section (Inventory Dashboard)
            const defaultSection = document.getElementById('inventory_dashboard_section');
            if (defaultSection) {
                defaultSection.classList.add('active');
            }
    
            // Set default active link
            const defaultLink = document.querySelector('.sub-navigation a:first-child');
            if (defaultLink) {
                defaultLink.classList.add('active');
            }
        };
    </script>

    <!--script for searchable -->
    <script>
       document.addEventListener("DOMContentLoaded", () => {
            const itemListSearchInput = document.getElementById("item-list-search-input");
            const itemListSearchButton = document.getElementById("item-list-search-btn");
            const inventoryTableBody = document.querySelector(".item_list_table tbody");

            // Function to fetch inventory data with search filter
            function fetchInventory(searchQuery = "") {
                let url = "php/fetch_inventory.php";
                if (searchQuery) {
                    url += `?search=${encodeURIComponent(searchQuery)}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            populateTable(data.items);
                        } else {
                            console.error("Error fetching inventory:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch Error:", error));
            }

            // Function to populate the table with search results
            function populateTable(items) {
                inventoryTableBody.innerHTML = ""; // Clear existing data

                if (items.length === 0) {
                    inventoryTableBody.innerHTML = `<tr><td colspan="13" style="text-align:center;">No results found</td></tr>`;
                    return;
                }

                items.forEach(item => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${item.InventoryID}</td>
                        <td>${item.ItemName}</td>
                        <td>${item.Category}</td>
                        <td>${item.Description}</td>
                        <td>${item.QuantityAvailable}</td>
                        <td>${item.ReorderPoints}</td>
                        <td>${item.UnitType}</td>
                        <td>₱${parseFloat(item.UnitPrice).toFixed(2)}</td>
                        <td>${item.Supplier}</td>
                        <td>${item.ExpiryDate}</td>
                        <td>${item.LastRestockedDate}</td>
                        <td>${item.Location}</td>
                        <td><button class="update_btn" data-id="${item.InventoryID}">UPDATE STOCK LEVEL</button></td>
                    `;
                    inventoryTableBody.appendChild(row);
                });
            }

            // Trigger search when clicking the search button
            itemListSearchButton.addEventListener("click", () => {
                const query = itemListSearchInput.value.trim();
                fetchInventory(query);
            });

            // Trigger search when pressing "Enter" inside the input field
            itemListSearchInput.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    const query = itemListSearchInput.value.trim();
                    fetchInventory(query);
                }
            });

            // Load all inventory items initially
            fetchInventory();
        });
        </script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const inventoryModal = document.getElementById("modal");
    const addInventoryBtn = document.getElementById("add_btn");
    const closeInventoryModal = document.getElementById("close_modal");
    const inventoryForm = document.getElementById("inventory_form");
    const inventorySubmitBtn = document.querySelector(".submit-btn");
    // Updated IDs to match the HTML:
    const searchInput = document.getElementById("item-list-search-input");
    const searchButton = document.getElementById("item-list-search-btn");
    const inventoryTableBody = document.querySelector(".item_list_table tbody");
    let isEditMode = false;
    let currentEditRow = null;

    // Function to fetch inventory data (with optional search query)
    function fetchInventory(searchQuery = "") {
        let url = "php/fetch_inventory.php";
        if (searchQuery) {
            url += `?search=${encodeURIComponent(searchQuery)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    populateTable(data.items);
                } else {
                    console.error("Error fetching inventory:", data.message);
                }
            })
            .catch(error => console.error("Fetch Error:", error));
    }

    // Function to populate the table with fetched data
    function populateTable(items) {
        inventoryTableBody.innerHTML = ""; // Clear existing data

        if (items.length === 0) {
            inventoryTableBody.innerHTML = `<tr><td colspan="13" style="text-align:center;">No results found</td></tr>`;
            return;
        }

        items.forEach(item => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${item.InventoryID}</td>
                <td>${item.ItemName}</td>
                <td>${item.Category}</td>
                <td>${item.Description}</td>
                <td>${item.QuantityAvailable}</td>
                <td>${item.ReorderPoints}</td>
                <td>${item.UnitType}</td>
                <td>₱${parseFloat(item.UnitPrice).toFixed(2)}</td>
                <td>${item.Supplier}</td>
                <td>${item.ExpiryDate}</td>
                <td>${item.LastRestockedDate}</td>
                <td>${item.Location}</td>
                <td>
                    <button class="update_btn" data-id="${item.InventoryID}">UPDATE STOCK LEVEL</button>
                </td>
            `;

            inventoryTableBody.appendChild(row);
        });
    }

    // Fetch inventory when page loads
    fetchInventory();

    // Search when user presses the search button
    searchButton.addEventListener("click", () => {
        const query = searchInput.value.trim();
        fetchInventory(query);
    });

    // Also trigger search when the user presses Enter
    searchInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            const query = searchInput.value.trim();
            fetchInventory(query);
        }
    });

    // OPEN MODAL FOR ADD
    addInventoryBtn.addEventListener("click", () => {
        isEditMode = false;
        inventoryModal.style.display = "flex";
        inventoryForm.reset();
        inventorySubmitBtn.textContent = "Submit";
    });

    // CLOSE MODAL
    closeInventoryModal.addEventListener("click", () => {
        inventoryModal.style.display = "none";
    });

    // Handle clicks on "UPDATE STOCK LEVEL" buttons using event delegation
    inventoryTableBody.addEventListener("click", (e) => {
        if (e.target.classList.contains("update_btn")) {
            const row = e.target.closest("tr"); // Get row of clicked button
            openEditModal(row);
        }
    });

    // Function to open edit modal and populate the form with selected row data
    function openEditModal(row) {
        isEditMode = true;
        currentEditRow = row;

        // Populate the form fields using table cell data
        inventoryForm.elements["ItemName"].value = row.cells[1].textContent;
        inventoryForm.elements["Category"].value = row.cells[2].textContent;
        inventoryForm.elements["Description"].value = row.cells[3].textContent;
        inventoryForm.elements["QuantityAvailable"].value = row.cells[4].textContent;
        inventoryForm.elements["ReorderPoints"].value = row.cells[5].textContent;
        inventoryForm.elements["UnitType"].value = row.cells[6].textContent;
        inventoryForm.elements["UnitPrice"].value = row.cells[7].textContent.replace("₱", "").trim();
        inventoryForm.elements["Supplier"].value = row.cells[8].textContent;
        inventoryForm.elements["ExpiryDate"].value = row.cells[9].textContent;
        inventoryForm.elements["LastRestockedDate"].value = row.cells[10].textContent;
        inventoryForm.elements["Location"].value = row.cells[11].textContent;

        // Open the modal
        inventoryModal.style.display = "flex";
        inventorySubmitBtn.textContent = "Update";
    }

    // Form submission for add/edit
    inventoryForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(inventoryForm);
        formData.append("action", isEditMode ? "update" : "add");

        if (isEditMode) {
            formData.append("InventoryID", currentEditRow.cells[0].textContent.trim());
        }

        fetch("php/inventory_handler.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(text => {
            console.log("Server Response:", text);
            try {
                return JSON.parse(text);
            } catch (err) {
                console.error("Server returned non-JSON response:", text);
                throw new Error("Invalid JSON response from server.");
            }
        })
        .then(data => {
            if (data.status === "success") {
                alert(data.message);
                fetchInventory();
                inventoryModal.style.display = "none";
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => console.error("Fetch Error:", error));
    });
});
</script>


    <!-- script for input the data in modal update-->
    <script>
       document.addEventListener("DOMContentLoaded", () => {
            const inventoryModal = document.getElementById("modal");
            const inventoryForm = document.getElementById("inventory_form");
            const inventorySubmitBtn = document.querySelector(".submit-btn");
            let currentInventoryID = null;

            document.querySelectorAll(".update_btn").forEach(button => {
                button.addEventListener("click", (e) => {
                    currentInventoryID = e.target.dataset.id;

                    // Fill modal fields with selected row data
                    inventoryForm.elements["ItemName"].value = e.target.dataset.name;
                    inventoryForm.elements["Category"].value = e.target.dataset.category;
                    inventoryForm.elements["Description"].value = e.target.dataset.description;
                    inventoryForm.elements["QuantityAvailable"].value = e.target.dataset.quantity;
                    inventoryForm.elements["ReorderPoints"].value = e.target.dataset.reorder;
                    inventoryForm.elements["UnitType"].value = e.target.dataset.unit;
                    inventoryForm.elements["UnitPrice"].value = e.target.dataset.price;
                    inventoryForm.elements["Supplier"].value = e.target.dataset.supplier;
                    inventoryForm.elements["ExpiryDate"].value = e.target.dataset.expiry;
                    inventoryForm.elements["LastRestockedDate"].value = e.target.dataset.restocked;
                    inventoryForm.elements["Location"].value = e.target.dataset.location;

                    inventoryModal.style.display = "flex";
                    inventorySubmitBtn.textContent = "Update";
                });
            });
        });

    </script>

    <!-- script for monitor track usage add modal-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const usageModal = document.getElementById("usage_modal");
            const addUsageBtn = document.getElementById("add_usage_btn");
            const closeUsageModal = document.getElementById("close_usage_modal");
            const usageForm = document.getElementById("usage_form");
            const usageTableBody = document.querySelector(".usage_table tbody");
            const usageModalTitle = document.getElementById("usage_modal_title");
            const usageSubmitBtn = usageModal.querySelector(".submit-btn");
            let isEditMode = false;
            let currentEditID = null;

            /** Fetch and populate usage records */
            function fetchUsageRecords(searchQuery = "") {
                let url = "php/fetch_usage_records.php";
                if (searchQuery) url += `?search=${encodeURIComponent(searchQuery)}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            populateTable(data.items);
                        } else {
                            console.error("Error fetching usage records:", data.message);
                        }
                    })
                    .catch(error => console.error("Fetch Error:", error));
            }

            /** Populate the table */
            function populateTable(items) {
                usageTableBody.innerHTML = "";
                if (items.length === 0) {
                    usageTableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">No records found</td></tr>`;
                    return;
                }

                items.forEach(item => {
                    const row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${item.UsageID}</td>
                        <td>${item.PatientFullName}</td>
                        <td>${item.ProcedureName}</td>
                        <td>${item.DateOfProcedure}</td>
                        <td>${item.ItemUsed}</td>
                        <td>${item.Quantity}</td>
                        <td>${item.UnitType}</td>
                        <td><button class="edit_usage_btn" data-id="${item.UsageID}">EDIT</button></td>
                    `;
                    usageTableBody.appendChild(row);
                });
            }

            /** Open modal for adding a new usage record */
            addUsageBtn.addEventListener("click", () => {
                isEditMode = false;
                currentEditID = null;
                usageModal.style.display = "flex";
                usageModalTitle.textContent = "Add Usage Record";
                usageSubmitBtn.textContent = "Save";
                usageForm.reset();
            });

            /** Close modal when clicking "X" */
            closeUsageModal.addEventListener("click", () => {
                usageModal.style.display = "none";
            });

            /** Close modal when clicking outside */
            window.addEventListener("click", (e) => {
                if (e.target === usageModal) {
                    usageModal.style.display = "none";
                }
            });

            /** Event delegation for editing records */
            usageTableBody.addEventListener("click", (e) => {
                if (e.target.classList.contains("edit_usage_btn")) {
                    const usageID = e.target.dataset.id;
                    openEditModal(usageID);
                }
            });

            /** Function to open the edit modal */
            function openEditModal(usageID) {
                isEditMode = true;
                currentEditID = usageID;

                fetch(`php/get_usage_item.php?id=${usageID}`)
                    .then(response => response.text())  // Get raw response first
                    .then(text => {
                        console.log(" Raw Server Response:", text); // Debugging
                        try {
                            return JSON.parse(text); // Attempt to parse JSON
                        } catch (error) {
                            console.error(" Invalid JSON from server:", text);
                            throw new Error("Server returned invalid JSON.");
                        }
                    })
                    .then(item => {
                        if (item.status === "success") {
                            const data = item.item;
                            document.getElementById("PatientID").value = data.PatientID;
                            document.getElementById("ProcedureName").value = data.ProcedureName;
                            document.getElementById("DateOfProcedure").value = data.DateOfProcedure;
                            document.getElementById("ItemUsed").value = data.ItemUsed;
                            document.getElementById("Quantity").value = data.Quantity;
                            document.getElementById("UnitType").value = data.UnitType;

                            usageModal.style.display = "flex";
                            usageModalTitle.textContent = "Edit Usage Record";
                            usageSubmitBtn.textContent = "Update";
                        } else {
                            alert("Error: " + item.message);
                        }
                    })
                    .catch(error => console.error(" Fetch Error:", error));
            }


            /** Handle form submission (add & update) */
            usageForm.addEventListener("submit", (e) => {
                e.preventDefault();

                // Debugging: Log form data before submission
                const formData = new FormData(usageForm);
                console.log(" Form Data Sent:", Object.fromEntries(formData));

                formData.append("action", isEditMode ? "update" : "add");

                if (isEditMode) {
                    formData.append("UsageID", currentEditID);
                }

                fetch("php/usage_handler.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log(" Server Response:", text); // Debugging: Log raw response
                    try {
                        return JSON.parse(text); // Parse JSON response
                    } catch (error) {
                        console.error(" Invalid JSON from server:", text);
                        throw new Error("Server returned invalid JSON.");
                    }
                })
                .then(data => {
                    if (data.status === "success") {
                        alert(data.message);
                        location.reload(); // Refresh table after success
                    } else {
                        alert(" Error: " + data.message);
                    }
                })
                .catch(error => console.error(" Fetch Error:", error));
            });

            // Initial fetch for usage records
            fetchUsageRecords();
        });
    </script>

    <!-- script for monitor track usage search-->
    <script>
    function handleUsageSearch() {
        const usageSearchInput = document.getElementById("usage-search-input");
        const usageSearchButton = document.getElementById("usage-search-btn");
        const usageTableBody = document.querySelector(".usage_table tbody");

        const searchQuery = usageSearchInput.value.trim();
        console.log("Searching for:", searchQuery);

        // Fetch data from the server
        fetch(`php/fetch_monitor_records.php?search=${encodeURIComponent(searchQuery)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then(data => {
                console.log("Parsed Data:", data);
                if (data.status === "success" && data.items.length > 0) {
                    populateTable(data.items);
                } else {
                    // Display "No records found" if no data is returned
                    console.log("No records found for the search query.");
                    usageTableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">No records found</td></tr>`;
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                usageTableBody.innerHTML = `<tr><td colspan="8" style="text-align:center;">Error loading data. Please try again.</td></tr>`;
            });
    }

    function populateTable(items) {
        const tableBody = document.getElementById("usage-table-body");
        tableBody.innerHTML = ""; // Clear existing rows

        items.forEach(item => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${item.UsageID}</td>
                <td>${item.PatientFullName}</td>
                <td>${item.ProcedureName}</td>
                <td>${item.DateOfProcedure}</td>
                <td>${item.ItemUsed}</td>
                <td>${item.Quantity}</td>
                <td>${item.UnitType}</td>
                <td><button class="edit_usage_btn" data-id="${item.UsageID}"><span class="material-symbols-outlined">edit</span>EDIT</button></td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Add event listener for Enter key in the search input
    document.getElementById("usage-search-input").addEventListener("keypress", (e) => {
        if (e.key === "Enter") {
            e.preventDefault();
            handleUsageSearch();
        }
    });

    // Fetch initial data when the page loads
    handleUsageSearch();
    </script>   

    <!-- Script for supplier -->
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const supplierModal = document.getElementById("supplier_modal");
        const addSupplierBtn = document.getElementById("add_supplier_btn");
        const closeSupplierModal = document.getElementById("close_supplier_modal");
        const supplierForm = document.getElementById("supplier_form");
        const supplierModalTitle = document.getElementById("supplier_modal_title");
        const supplierTableBody = document.getElementById("supplier-table-body");
        const searchInput = document.getElementById("supplier-search-input");
        const searchBtn = document.getElementById("supplier-search-btn");
        let isEditMode = false;
        let currentSupplierID = null;

        // Fetch and display suppliers. Accepts an optional search term.
        function fetchSuppliers(searchTerm = "") {
            let url = "php/fetch_suppliers.php";
            if (searchTerm) {
                url += "?search=" + encodeURIComponent(searchTerm);
            }
            fetch(url)
                .then(response => response.text())
                .then(text => {
                    console.log("Raw Server Response:", text);
                    try {
                        const data = JSON.parse(text);
                        if (data.status === "success") {
                            if (Array.isArray(data.data)) {
                                populateTable(data.data);
                            } else {
                                console.error("Invalid data format: data.data is not an array");
                            }
                        } else {
                            console.error("Error fetching suppliers:", data.message);
                        }
                    } catch (error) {
                        console.error("Invalid JSON from server:", text);
                        throw new Error("Server returned invalid JSON.");
                    }
                })
                .catch(error => console.error("Fetch Error:", error));
        }

        // Populate the table with supplier data
        function populateTable(suppliers) {
            supplierTableBody.innerHTML = "";
            suppliers.forEach(supplier => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${supplier.SupplierID}</td>
                    <td>${supplier.SupplierName}</td>
                    <td>${supplier.Status}</td>
                    <td>${supplier.ContactPerson}</td>
                    <td>${supplier.ContactNumber}</td>
                    <td>${supplier.Email}</td>
                    <td>${supplier.Address}</td>
                    <td>${supplier.City}</td>
                    <td>${supplier.PostalCode}</td>
                    <td><button class="edit_supplier_btn" data-id="${supplier.SupplierID}">EDIT</button></td>
                `;
                supplierTableBody.appendChild(row);
            });
        }

        // Open modal for adding a new supplier
        addSupplierBtn.addEventListener("click", () => {
            console.log("Add button clicked");
            isEditMode = false;
            supplierModalTitle.textContent = "Add Supplier";
            supplierForm.reset();
            supplierModal.style.display = "flex";
        });

        // Open modal for editing a supplier
        supplierTableBody.addEventListener("click", (e) => {
            if (e.target.classList.contains("edit_supplier_btn")) {
                console.log("Edit button clicked");
                isEditMode = true;
                currentSupplierID = e.target.dataset.id;
                const row = e.target.closest("tr");
                supplierForm.SupplierName.value = row.cells[1].textContent;
                supplierForm.Status.value = row.cells[2].textContent;
                supplierForm.ContactPerson.value = row.cells[3].textContent;
                supplierForm.ContactNumber.value = row.cells[4].textContent;
                supplierForm.Email.value = row.cells[5].textContent;
                supplierForm.Address.value = row.cells[6].textContent;
                supplierForm.City.value = row.cells[7].textContent;
                supplierForm.PostalCode.value = row.cells[8].textContent;
                supplierModalTitle.textContent = "Edit Supplier";
                supplierModal.style.display = "flex";
            }
        });

        // Close modal
        closeSupplierModal.addEventListener("click", () => {
            console.log("Close button clicked");
            supplierModal.style.display = "none";
        });

        // Handle form submission (add/edit)
        supplierForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(supplierForm);
            const postalCode = formData.get("PostalCode");
            const contactNumber = formData.get("ContactNumber");

            // Validate Postal Code (4 digits)
            if (!/^\d{4}$/.test(postalCode)) {
                alert("Postal Code must be exactly 4 digits.");
                return;
            }
            // Validate Contact Number (9 digits)
            if (!/^\d{9}$/.test(contactNumber)) {
                alert("Contact Number must be exactly 9 digits.");
                return;
            }
            // Add action and SupplierID (if in edit mode) to form data
            formData.append("action", isEditMode ? "edit" : "add");
            if (isEditMode) {
                formData.append("SupplierID", currentSupplierID);
            }
            fetch("php/supplier_handler.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(text => {
                console.log("Server Response:", text);
                try {
                    const data = JSON.parse(text);
                    if (data.status === "success") {
                        alert(data.message);
                        fetchSuppliers(searchInput.value.trim()); // Refresh the table (with current search, if any)
                        supplierModal.style.display = "none";
                    } else {
                        alert("Error: " + data.message);
                    }
                } catch (error) {
                    console.error("Invalid JSON from server:", text);
                    throw new Error("Server returned invalid JSON.");
                }
            })
            .catch(error => {
                console.error("Fetch Error:", error);
                alert("An error occurred. Please try again.");
            });
        });

        // Add event listener for search button
        searchBtn.addEventListener("click", () => {
            const searchTerm = searchInput.value.trim();
            fetchSuppliers(searchTerm);
        });

        // Optionally, you can also trigger search on "Enter" key press in the search input:
        searchInput.addEventListener("keypress", (e) => {
            if (e.key === "Enter") {
                const searchTerm = searchInput.value.trim();
                fetchSuppliers(searchTerm);
            }
        });

        // Fetch all suppliers on page load
        fetchSuppliers();
    });
    </script>

    <!-- script for chart-->
    <script src="https://cdn.jsdelivr.net/npm/chart.js" ></script>
    <script>
        // Pass PHP variables to JavaScript
        const labels = <?php echo json_encode($months); ?>;
        const dataValues = <?php echo json_encode($values); ?>;

        const data = {
            labels: labels,
            datasets: [{
                label: 'Inventory Usage',
                data: dataValues,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            // Adjust stepSize or other tick options as needed
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const monthlyInventoryChart = new Chart(
            document.getElementById('monthly-inventory-chart'),
            config
        );
    </script>


</body>
</html>