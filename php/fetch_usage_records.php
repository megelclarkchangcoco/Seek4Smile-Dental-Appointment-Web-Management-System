<?php
include 'connection.php';
header('Content-Type: application/json');

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : "";

$sql = "SELECT u.*, CONCAT(p.Firstname, ' ', p.Lastname) AS PatientFullName 
        FROM usage_records u 
        JOIN patient p ON u.PatientID = p.PatientID";

if (!empty($searchQuery)) {
    $sql .= " WHERE p.Firstname LIKE ? OR p.Lastname LIKE ? OR u.ItemUsed LIKE ? OR u.UsageID LIKE ?";
}

$stmt = $connection->prepare($sql);

if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Database Prepare Error: " . $connection->error]);
    exit();
}

if (!empty($searchQuery)) {
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
}

$stmt->execute();
$result = $stmt->get_result();
$usageRecords = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usageRecords[] = $row;
    }
}

echo json_encode(["status" => "success", "items" => $usageRecords]);

$stmt->close();
$connection->close();
exit();
?>
