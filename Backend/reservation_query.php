<?php
include 'connect.php';
header('Content-Type: application/json');

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$reservation_id = $data['reservationNumber'];
$lastname = $data['lastName'];
$firstname = $data['firstName'];

$query = "SELECT * FROM Reservation WHERE lastname = '$lastname' AND firstname = '$firstname' AND reservation_id = '$reservation_id'";
$result = $connect->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'data' => $row]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No reservations found.']);
}

$connect -> close();

?>