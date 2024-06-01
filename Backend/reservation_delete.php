<?php

parse_str(file_get_contents("php://input"), $_DELETE);
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if ($id) {
    
    $stmt = $connect->prepare("DELETE FROM Reservation WHERE reservation_id = ?");
    $stmt->bind_param("s", $id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode(['success' => true, 'message' => '삭제 성공']);
    } else {
        echo json_encode(['success' => false, 'message' => '삭제 실패']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID is required']);
}

$connect->close();
?>