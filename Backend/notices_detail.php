<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connect.php';

// 공지사항 ID를 가져옵니다.
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id == 0) {
    echo json_encode(['error' => 'Invalid notice ID']);
    exit;
}

// 공지사항 세부 내용을 조회하는 쿼리
$query = "SELECT id, notice_date, title, content, body FROM Notice WHERE id = ?";
$stmt = $connect->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['error' => 'Notice not found']);
    exit;
}

$notice = $result->fetch_assoc();
echo json_encode($notice);

$stmt->close();
$connect->close();
?>
