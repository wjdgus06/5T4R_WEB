<?php
session_start();
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    echo json_encode(['error' => 'User ID not set in session']);
    exit();
}

$sql = "SELECT * FROM users WHERE user_id = '$user_id'";

$result = $connect->query($sql);
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit();
}

echo json_encode([
    'user' => $user
]);

$connect->close();

?>