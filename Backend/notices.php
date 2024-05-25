<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// 공지사항 데이터를 DB에서 가져오기

header('Content-Type: application/json');

$sql = "SELECT id, notice_date, title, body, content FROM Notice ORDER BY id DESC";
$result = $connect->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $connect->error]);
    exit;
}

$notices = [];
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}

echo json_encode($notices);

$connect->close();

?>
