<?php
include 'connection.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate GET parameter
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo json_encode(["status" => "error", "message" => "Missing or invalid Usage ID"]);
    exit();
}

$usageID = $_GET['id'];

// Fetch usage record
$stmt = $connection->prepare("SELECT * FROM usage_records WHERE UsageID = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $connection->error]);
    exit();
}

$stmt->bind_param("s", $usageID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
    echo json_encode(["status" => "success", "item" => $item]);
} else {
    echo json_encode(["status" => "error", "message" => "Usage record not found"]);
}

$stmt->close();
$connection->close();
exit();
?>
