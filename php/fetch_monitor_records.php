<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";

$sql = "SELECT ur.*, CONCAT(p.Firstname, ' ', p.Lastname) AS PatientFullName 
        FROM usage_records ur
        JOIN patient p ON ur.PatientID = p.PatientID";

if (!empty($searchQuery)) {
    $sql .= " WHERE ur.UsageID LIKE ? OR p.Firstname LIKE ? OR p.Lastname LIKE ? OR ur.ProcedureName LIKE ? OR ur.ItemUsed LIKE ?";
}

$stmt = $connection->prepare($sql);
if (!empty($searchQuery)) {
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
$usageRecords = [];

while ($row = $result->fetch_assoc()) {
    $usageRecords[] = $row;
}

echo json_encode(["status" => "success", "items" => $usageRecords]);
?>