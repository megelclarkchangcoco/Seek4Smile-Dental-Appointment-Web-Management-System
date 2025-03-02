<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ["debug" => $_POST];

//  Log incoming data for debugging (check "debug_log.txt" to verify)
//file_put_contents("debug_log.txt", print_r($_POST, true), FILE_APPEND);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // 🔹 Validate required fields before proceeding
    if (!isset($_POST['PatientID']) || empty(trim($_POST['PatientID']))) {
        $response["status"] = "error";
        $response["message"] = "Patient ID is missing.";
        echo json_encode($response);
        exit();
    }
    if (!isset($_POST['ProcedureName']) || empty(trim($_POST['ProcedureName']))) {
        $response["status"] = "error";
        $response["message"] = "Procedure field is missing.";
        echo json_encode($response);
        exit();
    }
    if (!isset($_POST['DateOfProcedure']) || empty(trim($_POST['DateOfProcedure']))) {
        $response["status"] = "error";
        $response["message"] = "Date of Procedure is missing.";
        echo json_encode($response);
        exit();
    }
    if (!isset($_POST['ItemUsed']) || empty(trim($_POST['ItemUsed']))) {
        $response["status"] = "error";
        $response["message"] = "Item Used is missing.";
        echo json_encode($response);
        exit();
    }
    if (!isset($_POST['Quantity']) || empty(trim($_POST['Quantity'])) || !is_numeric($_POST['Quantity']) || $_POST['Quantity'] <= 0) {
        $response["status"] = "error";
        $response["message"] = "Invalid quantity value.";
        echo json_encode($response);
        exit();
    }
    if (!isset($_POST['UnitType']) || empty(trim($_POST['UnitType']))) {
        $response["status"] = "error";
        $response["message"] = "Unit Type is missing.";
        echo json_encode($response);
        exit();
    }

    // 🔹 If adding a new usage record
    if ($action === 'add') {
        $stmt = $connection->prepare("INSERT INTO usage_records 
            (PatientID, ProcedureName, DateOfProcedure, ItemUsed, Quantity, UnitType)
            VALUES (?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            $response["status"] = "error";
            $response["message"] = "SQL Prepare Error: " . $connection->error;
            echo json_encode($response);
            exit();
        }

        // ✅ Bind parameters and execute query
        $stmt->bind_param("ssssis",
            $_POST['PatientID'], $_POST['ProcedureName'], $_POST['DateOfProcedure'],
            $_POST['ItemUsed'], $_POST['Quantity'], $_POST['UnitType']
        );

        if ($stmt->execute()) {
            // 🔹 Deduct quantity from inventory
            $updateStmt = $connection->prepare("UPDATE inventory SET QuantityAvailable = QuantityAvailable - ? WHERE ItemName = ?");
            if ($updateStmt) {
                $updateStmt->bind_param("is", $_POST['Quantity'], $_POST['ItemUsed']);
                $updateStmt->execute();
                $updateStmt->close();
            } else {
                file_put_contents("debug_log.txt", "Inventory update failed: " . $connection->error, FILE_APPEND);
            }

            $response["status"] = "success";
            $response["message"] = "Usage record added successfully!";
        } else {
            $response["status"] = "error";
            $response["message"] = "Database Insert Error: " . $stmt->error;
        }

        $stmt->close();
    }

    // 🔹 If updating an existing usage record
    elseif ($action === 'update') {
        if (!isset($_POST['UsageID']) || empty(trim($_POST['UsageID']))) {
            $response["status"] = "error";
            $response["message"] = "Usage ID is missing.";
            echo json_encode($response);
            exit();
        }

        $stmt = $connection->prepare("UPDATE usage_records SET 
            PatientID=?, ProcedureName=?, DateOfProcedure=?, ItemUsed=?, Quantity=?, UnitType=? 
            WHERE UsageID=?");

        if (!$stmt) {
            $response["status"] = "error";
            $response["message"] = "SQL Prepare Error: " . $connection->error;
            echo json_encode($response);
            exit();
        }

        // ✅ Bind parameters and execute query
        $stmt->bind_param("ssssiss",
            $_POST['PatientID'], $_POST['ProcedureName'], $_POST['DateOfProcedure'],
            $_POST['ItemUsed'], $_POST['Quantity'], $_POST['UnitType'], $_POST['UsageID']
        );

        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "Usage record updated successfully!";
        } else {
            $response["status"] = "error";
            $response["message"] = "Database Update Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// ✅ Close connection & return response
$connection->close();
echo json_encode($response);
exit();
?>