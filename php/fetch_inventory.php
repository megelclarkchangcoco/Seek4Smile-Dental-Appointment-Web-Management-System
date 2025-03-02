<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";

// Base SQL query
$sql = "SELECT * FROM inventory";

// If search query exists, add WHERE conditions
if (!empty($searchQuery)) {
    $sql .= " WHERE ItemName LIKE ? OR InventoryID LIKE ? OR Category LIKE ? OR Supplier LIKE ?";
}

// Prepare the SQL statement
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
$inventory = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $inventory[] = $row;
    }
}

// Return data in JSON format
echo json_encode(["status" => "success", "items" => $inventory]);

// Close statement and connection
$stmt->close();
$connection->close();
exit();
?>
