<?php
session_start();
include('database/dbconfig.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id']) || !isset($data['latitude']) || !isset($data['longitude'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$user_id = $data['user_id'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Update user's location in the database
$query = "UPDATE register SET latitude = ?, longitude = ? WHERE id = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ddi", $latitude, $longitude, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Location updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update location']);
}

$stmt->close();
?> 