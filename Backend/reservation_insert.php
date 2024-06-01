<?php
session_start();  // 세션 시작
header('Content-Type: application/json');


$input = file_get_contents('php://input');
$data = json_decode($input, true);


if (isset($_SESSION['user_id'])) {
    $user = $_SESSION['user_id'];
} else {
    echo json_encode(array("status" => "error", "message" => "User is not logged in."));
    exit;
}

function generateSecureRandomID($connect) {
    do {
        $numbers = '';
        $letters = '';

        for ($i = 0; $i < 5; $i++) {
            $numbers .= random_int(0, 9);
        }

        for ($i = 0; $i < 2; $i++) {
            $letters .= chr(random_int(65, 90));
        }

        $reservation_id = $numbers . $letters;

        // reservation_id의 중복 확인
        $query = "SELECT count(*) FROM Reservation WHERE reservation_id = '" . $connect->real_escape_string($reservation_id) . "'";
        $result = $connect->query($query);
        if (!$result) {
            die("Database query failed: " . $connect->error);
        }
        $row = $result->fetch_array();
        $count = $row[0];

    } while ($count > 0);

    return $reservation_id;
}

$reservation_id = generateSecureRandomID($connect);

$flight_id = $data['flight_id'];
$price = $data['price'];
$passengers = $data['passengers'];
$lastName = $data['lastName'];
$firstName = $data['firstName'];

$sql = "INSERT INTO Reservation (reservation_id, user_id, flight_id, price, lastname, firstname) VALUES ('$reservation_id', '$user', '$flight_id', '$price', '$lastName', '$firstName')";
$result = $connect->query($sql);

if (!$result) {
    echo json_encode(array("status" => "error", "message" => "Error executing query: " . $connect->error));
    exit;
}

foreach ($passengers as $passenger) {
    $firstName = $connect->real_escape_string($passenger['firstName']);
    $lastName = $connect->real_escape_string($passenger['lastName']);
    $birthDate = $connect->real_escape_string($passenger['birthDate']);
    $phone = $connect->real_escape_string($passenger['phone']);
    $passport = $connect->real_escape_string($passenger['passport']);

    $sql = "INSERT INTO Passenger_info (reservation_id, FirstName, LastName, Birth, phone, passport_number) VALUES ('$reservation_id', '$firstName', '$lastName', '$birthDate', '$phone', '$passport')";
    $result = $connect->query($sql);
    if (!$result) {
        echo json_encode(array("status" => "error", "message" => "Error executing query: " . $connect->error));
        exit;
    }
}
echo json_encode(array("status" => "success", "message" => "Reservation and passenger information saved successfully!", "reservation_id" => $reservation_id));

$connect->close();
?>