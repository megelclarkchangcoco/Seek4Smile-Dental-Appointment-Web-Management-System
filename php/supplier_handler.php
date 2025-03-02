<?php
include 'connection.php'; // Include your database connection file
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$action = $_POST['action']; // Determine if it's an add or edit action

if ($action === 'add') {
    // Retrieve form data for adding a supplier
    $supplierName  = $_POST['SupplierName'];
    $status        = $_POST['Status'];
    $contactPerson = $_POST['ContactPerson'];
    $contactNumber = $_POST['ContactNumber'];
    $email         = $_POST['Email'];
    $address       = $_POST['Address'];
    $city          = $_POST['City'];
    $postalCode    = $_POST['PostalCode'];

    // Validate Postal Code (4 digits)
    if (!preg_match('/^\d{4}$/', $postalCode)) {
        echo json_encode(["status" => "error", "message" => "Postal Code must be exactly 4 digits."]);
        exit();
    }

    // Validate Contact Number (9 digits)
    if (!preg_match('/^\d{9}$/', $contactNumber)) {
        echo json_encode(["status" => "error", "message" => "Contact Number must be exactly 9 digits."]);
        exit();
    }

    $sql = "INSERT INTO suppliers (SupplierName, Status, ContactPerson, ContactNumber, Email, Address, City, PostalCode)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database Prepare Error: " . $connection->error]);
        exit();
    }

    // Bind parameters (all values assumed to be strings)
    $stmt->bind_param("ssssssss", $supplierName, $status, $contactPerson, $contactNumber, $email, $address, $city, $postalCode);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Database Execution Error: " . $stmt->error]);
        exit();
    }

    echo json_encode(["status" => "success", "message" => "Supplier added successfully!"]);
    $stmt->close();
    $connection->close();
    exit();
} elseif ($action === 'edit') {
    // Retrieve form data for editing a supplier
    $supplierID    = $_POST['SupplierID'];
    $supplierName  = $_POST['SupplierName'];
    $status        = $_POST['Status'];
    $contactPerson = $_POST['ContactPerson'];
    $contactNumber = $_POST['ContactNumber'];
    $email         = $_POST['Email'];
    $address       = $_POST['Address'];
    $city          = $_POST['City'];
    $postalCode    = $_POST['PostalCode'];

    // Validate Postal Code (4 digits)
    if (!preg_match('/^\d{4}$/', $postalCode)) {
        echo json_encode(["status" => "error", "message" => "Postal Code must be exactly 4 digits."]);
        exit();
    }

    // Validate Contact Number (9 digits)
    if (!preg_match('/^\d{9}$/', $contactNumber)) {
        echo json_encode(["status" => "error", "message" => "Contact Number must be exactly 9 digits."]);
        exit();
    }

    $sql = "UPDATE suppliers 
            SET SupplierName = ?, Status = ?, ContactPerson = ?, ContactNumber = ?, Email = ?, Address = ?, City = ?, PostalCode = ?
            WHERE SupplierID = ?";
    $stmt = $connection->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Database Prepare Error: " . $connection->error]);
        exit();
    }

    // Bind parameters.
    // Here we assume SupplierID is an integer so we use "i" for that type.
    $stmt->bind_param("ssssssssi", $supplierName, $status, $contactPerson, $contactNumber, $email, $address, $city, $postalCode, $supplierID);

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Database Execution Error: " . $stmt->error]);
        exit();
    }

    echo json_encode(["status" => "success", "message" => "Supplier updated successfully!"]);
    $stmt->close();
    $connection->close();
    exit();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid action."]);
    exit();
}
?>
