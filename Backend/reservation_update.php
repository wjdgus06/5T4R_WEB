<?php

$input = file_get_contents('php://input');
$data = json_decode($input, true);

$reservationId = $data['reservationId'];
$status = $data['status'];

$sql = "update Reservation set status='$status' where reservation_id = '$reservationId'";
$result = $connect->query($sql);

header('Content-Type: application/json');

if ($result) {
    echo json_encode(['success' => true, 'message' => '수정 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '수정 실패']);
}

$connect->close();
?>
