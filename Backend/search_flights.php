<?php
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$input = file_get_contents('php://input');
$data = json_decode($input, true);

error_log("Input data: " . print_r($data, true));

$departure = $data['departure'];
$destination = $data['destination'];
$startDate = $data['startDate'];
$endDate = $data['endDate'];
$passengers = $data['passengers'];


$sql = "SELECT * FROM Flight_info 
        WHERE (departure_airport = '$departure' AND arrival_airport = '$destination' AND departure_day = '$startDate')
        OR (departure_airport = '$destination' AND arrival_airport = '$departure' AND departure_day = '$endDate')";
$result = $connect->query($sql);

if (!$result) {
    error_log("Database query failed: " . $connect->error);
    echo json_encode(['error' => 'Database query failed']);
    exit;
}
if ($result->num_rows == 0) {
    echo json_encode(['error' => 'Flight not found']);
    console.log("no");
    exit;
}

$flights = [];
while ($row = $result->fetch_assoc()) {
    $flights[] = [
        'flightNumber' => $row['flight_number'],
        'departureAirport' => $row['departure_airport'],
        'arrivalAirport' => $row['arrival_airport'],
        'departureDay' => $row['departure_day'],
        'arrivalDay' => $row['arrival_day'],
        'status' => $row['status'],
        'departureTime' => $row['departure_time'],
        'arrivalTime' => $row['arrival_time'],
        // 'Price' => '140,000','
    ];
}

echo json_encode($flights);

$connect->close();
?>
