<?php

include 'connect.php';

$reservationId = isset($_GET['reservationId']) ? $_GET['reservationId'] : '';

if (!$reservationId) {
    echo json_encode(['status' => 'error', 'message' => 'No reservation ID provided']);
    exit;
}

$query = "SELECT price FROM Reservation WHERE reservation_id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param("s", $reservationId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(['status' => 'success', 'price' => $row['price']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No reservation found with the provided ID']);
}

$stmt->close();
$connect->close();
?>
