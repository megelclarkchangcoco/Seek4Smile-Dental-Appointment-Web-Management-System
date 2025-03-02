<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
include 'connection.php';

$response = []; // Initialize response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        if (!isset($_POST['ItemName']) || empty(trim($_POST['ItemName']))) {
            $response["status"] = "error";
            $response["message"] = "ItemName is missing or empty.";
            echo json_encode($response);
            exit();
        }

        // Prepare the INSERT statement
        $stmt = $connection->prepare("INSERT INTO inventory 
            (ItemName, Category, Description, QuantityAvailable, ReorderPoints, UnitType, UnitPrice, Supplier, ExpiryDate, LastRestockedDate, Location)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
        if (!$stmt) {
            $response["status"] = "error";
            $response["message"] = "Prepare failed: " . $connection->error;
            echo json_encode($response);
            exit();
        }

        // Corrected format: UnitType is a string (s)
        $stmt->bind_param("sssiisdssss",
            $_POST['ItemName'], 
            $_POST['Category'], 
            $_POST['Description'],
            $_POST['QuantityAvailable'], 
            $_POST['ReorderPoints'], 
            $_POST['UnitType'],    // This is now a string
            $_POST['UnitPrice'], 
            $_POST['Supplier'], 
            $_POST['ExpiryDate'],
            $_POST['LastRestockedDate'], 
            $_POST['Location']
        );

        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "Item added successfully!";
        } else {
            $response["status"] = "error";
            $response["message"] = "Database Error: " . $stmt->error;
        }

        $stmt->close();
    } 
    
    elseif ($action === 'update') {
        if (!isset($_POST['InventoryID']) || empty(trim($_POST['InventoryID']))) {
            $response["status"] = "error";
            $response["message"] = "Inventory ID is missing or empty.";
            echo json_encode($response);
            exit();
        }

        // Prepare the UPDATE statement
        $stmt = $connection->prepare("UPDATE inventory SET 
            ItemName=?, Category=?, Description=?, QuantityAvailable=?, ReorderPoints=?, UnitType=?, UnitPrice=?, Supplier=?, ExpiryDate=?, LastRestockedDate=?, Location=? 
            WHERE InventoryID=?");

        if (!$stmt) {
            $response["status"] = "error";
            $response["message"] = "Prepare failed: " . $connection->error;
            echo json_encode($response);
            exit();
        }

        // Debug info (optional)
        $response["debug"] = [
            "ItemName" => $_POST['ItemName'],
            "Category" => $_POST['Category'],
            "Description" => $_POST['Description'],
            "QuantityAvailable" => $_POST['QuantityAvailable'],
            "ReorderPoints" => $_POST['ReorderPoints'],
            "UnitType" => $_POST['UnitType'],
            "UnitPrice" => $_POST['UnitPrice'],
            "Supplier" => $_POST['Supplier'],
            "ExpiryDate" => $_POST['ExpiryDate'],
            "LastRestockedDate" => $_POST['LastRestockedDate'],
            "Location" => $_POST['Location'],
            "InventoryID" => $_POST['InventoryID']
        ];

        // Corrected format string: UnitType is a string (s)
        $stmt->bind_param("sssiisdsssss",
            $_POST['ItemName'], 
            $_POST['Category'], 
            $_POST['Description'],
            $_POST['QuantityAvailable'], 
            $_POST['ReorderPoints'], 
            $_POST['UnitType'],   // Now a string
            $_POST['UnitPrice'], 
            $_POST['Supplier'], 
            $_POST['ExpiryDate'],
            $_POST['LastRestockedDate'], 
            $_POST['Location'], 
            $_POST['InventoryID']
        );

        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "Stock level updated successfully!";
        } else {
            $response["status"] = "error";
            $response["message"] = "Database Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$connection->close();
echo json_encode($response);
exit();
?>
