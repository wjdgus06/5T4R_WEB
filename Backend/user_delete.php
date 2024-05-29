<?php

header('Content-Type: application/json');
session_start();

$user_id = $_SESSION['user_id'];

error_log("DELETE data: " . json_encode($_DELETE));

$sql = "DELETE FROM users WHERE user_id = '$user_id'";

$result = $connect->query($sql);

if ($result) {
    echo json_encode(['success' => true, 'message' => '삭제 성공']);
} else {
    echo json_encode(['success' => false, 'message' => '삭제 실패']);
}

$connect->close();
?>