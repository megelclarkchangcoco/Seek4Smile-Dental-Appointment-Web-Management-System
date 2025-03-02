<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";

// Base SQL query
$sql = "SELECT * FROM suppliers";

// If search query exists, filter results
if (!empty($searchQuery)) {
    $sql .= " WHERE SupplierID LIKE ? OR SupplierName LIKE ? OR ContactPerson LIKE ? OR Email LIKE ?";
}

$stmt = $connection->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database Prepare Error: " . $connection->error]);
    exit();
}

// Bind parameters only if there is a search query
if (!empty($searchQuery)) {
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

// Execute the query
if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Database Execution Error: " . $stmt->error]);
    exit();
}

// Fetch results
$result = $stmt->get_result();
$suppliers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row;
    }
}

// Return data in JSON format with the key "data"
echo json_encode(["status" => "success", "data" => $suppliers]);

// Close statement and connection
$stmt->close();
$connection->close();
exit();
?>
