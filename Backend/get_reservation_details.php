<?php
include 'connect.php';  // 데이터베이스 연결 설정 포함

header('Content-Type: application/json');

$reservation_id = $_GET['reservation_id'] ?? '';

if (!$reservation_id) {
    echo json_encode(['error' => 'Reservation ID is required']);
    exit;
}

// 예약 정보 조회
$reservationQuery = "SELECT * FROM Reservation WHERE reservation_id = '$reservation_id'";
$reservationResult = $connect->query($reservationQuery);

if (!$reservationResult) {
    echo json_encode(['error' => $connect->error]);
    exit;
}

$reservationData = $reservationResult->fetch_assoc();

if (!$reservationData) {
    echo json_encode(['error' => 'No reservation found']);
    exit;
}

// 항공편 정보 조회
$flightQuery = "SELECT * FROM Flight_info WHERE id = '{$reservationData['flight_id']}'";
$flightResult = $connect->query($flightQuery);
$flightData = $flightResult->fetch_assoc();

// 승객 정보 조회
$passengerQuery = "SELECT * FROM Passenger_info WHERE reservation_id = '$reservation_id'";
$passengerResult = $connect->query($passengerQuery);
$passengers = [];
while ($row = $passengerResult->fetch_assoc()) {
    $passengers[] = $row;
}

echo json_encode(['status' => 'success', 'data' => [
    'reservation' => $reservationData,
    'flight' => $flightData,
    'passengers' => $passengers
]]);

$connect->close();
?>
