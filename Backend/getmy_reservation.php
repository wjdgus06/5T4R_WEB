<?php
include 'connect.php';

header('Content-Type: application/json');

$user_id = isset($_GET['user_id']) ? $connect->real_escape_string($_GET['user_id']) : null;

if ($user_id) {
    $query = "SELECT * FROM Reservation WHERE user_id = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $user_id);  // 's'는 문자열 타입을 나타냄
    $stmt->execute();
    $result = $stmt->get_result();
    $reservations = [];

    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;  // 각 예약의 정보를 배열에 추가
    }

    echo json_encode(['reservations' => $reservations]);
    $stmt->close();
} else {
    echo json_encode(['error' => 'User ID is required']);
}

$connect->close();
?>
